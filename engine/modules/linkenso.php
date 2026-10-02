<?php

/*
=============================================================================
 Файл: linkenso.php (frontend) версия 2.0
-----------------------------------------------------------------------------
 Автор: Фомин Александр Алексеевич, mail@mithrandir.ru
-----------------------------------------------------------------------------
 Назначение: кольцевая перелинковка новостей на сайте
=============================================================================
*/

    // Антихакер
    if( !defined( 'DATALIFEENGINE' )) {
            die( "Hacking attempt!" );
    }

    /*
     * Класс для вывода рубрик сайта
     */
    if(!class_exists('LinkEnso')) { class LinkEnso
    {
        /*
         * Конструктор класса LinkEnso - задаёт значение свойства dle_api и стандартных переменных
         * @param $linkEnsoConfig - массив с конфигурацией модуля
         */
        public function __construct($linkEnsoConfig)
        {
            // Подключаем DLE_API
            global $db, $config, $category;
            include ('engine/api/api.class.php');
            $this->dle_api = $dle_api;
            
            // Задаем конфигуратор класса
            $this->config = $linkEnsoConfig;
        }


        /*
         * Главный метод класса LinkEnso
         */
        public function run()
        {
            // Пробуем подгрузить содержимое модуля из кэша
            $output = false;
            if($this->dle_api->dle_config['allow_cache'] == 'yes')
            {
                $output = $this->dle_api->load_from_cache('linkenso_'.md5(implode('_', $this->config)));
            }
            
            // Если значение кэша для данной конфигурации получено, выводим содержимое кэша
            if($output !== false)
            {
                $this->showOutput($output);
                return;
            }
            
            // Если в кэше ничего не найдено, генерируем модуль заново
            $wheres = array();
            
            // Получаем информацию о том, в каких категориях лежит данный пост
            $post = $this->dle_api->load_table (PREFIX."_post", 'category', 'id = '.$this->config['postId'], false);
            
            // Исправляем тупой косяк DLE API - такой метод ОБЯЗАН возвращать пустой массив в случае, если ничего не найдено
            if(!empty($post) && !empty($post['category'])) $postCategories = array();
            
            $postCategories = explode(',', $post['category']);
            
            // Получаем список категорий для выборки в зависимости от параметра scan
            $categoriesArray = array();
            switch($this->config['scan'])
            {
                // Если нужно сканировать только текущую категорию
                case 'same_cat':                    
                    // Каждую категорию текущего поста и все ее подкатегории добавляем в общий массив
                    foreach($postCategories as $postCategory)
                    {
                        $postCategory = intval($postCategory);
                        $categoriesArray[] = $postCategory;
                        $categoriesArray = array_merge($categoriesArray, $this->getSubcategoriesArray($postCategory));
                    }
                    break;
                
                // Если нужно сканировать все подкатегории самой "верхней категории"
                case 'global_cat':
                    // Для каждой из категорий текущего поста находим корневую категорию и все её подкатегории
                    foreach($postCategories as $postCategory)
                    {
                        $postCategory = intval($postCategory);
                        $globalCategoryId = $this->getGlobalCategory($postCategory);
                        $categoriesArray[] = $globalCategoryId;
                        $categoriesArray = array_merge($categoriesArray, $this->getSubcategoriesArray($globalCategoryId));
                    }
                    break;
                
                default:
                    break;
            }
            
            // Условие на список категорий
            if(count($categoriesArray) > 0)
            {
                switch($this->dle_api->dle_config['allow_multi_category'])
                {
                    // Если включена поддержка мультикатегорий
                    case '1':
                        $categoryWheres = array();
                        foreach($categoriesArray as $categoryId)
                        {
                            $categoryWheres[] = 'category regexp "[[:<:]]('.str_replace(',', '|', $categoryId).')[[:>:]]"';
                        }
                        $wheres[] = '('.implode(' OR ', $categoryWheres).')';
                        break;

                    // Если поддержки мультикатегорий нет
                    default:
                        $wheres[] = 'category IN ('.implode(',', $categoriesArray).')';
                        break;
                }               
            }
            
            // В зависимости от параметра date определяем старые нам посты нужны или новые
            switch($this->config['date'])
            {
                case 'new':
                    $dateWhere = 'id > '.$this->config['postId'];
                    break;
                
                default:
                    $dateWhere = 'id < '.$this->config['postId'];
                    break;
            }
            
            // Условие для отображения только постов, прошедших модерацию
            $wheres[] = 'approve = 1';

            // Условие для отображения только тех постов, дата публикации которых уже наступила
            $wheres[] = 'date < "'.date("Y-m-d H:i:s").'"';
            
            // Условие для фильтрации текущего id
            $wheres[] = 'id != '.$this->config['postId'];
            
            // Складываем условия
            $where = implode(' AND ', $wheres);
            
            // Направление сортировки зависит от того, свежие мы смотрим или старые (для свежих - ASC, для старых - DESC)
            $ordering = $this->config['date'] == 'new'?'ASC':'DESC';
            
            // Первый этап - получение предыдущих постов
            $posts = $this->dle_api->load_table (PREFIX."_post", '*', $where.' AND '.$dateWhere, true, 0, $this->config['links'], 'id', $ordering);

            // Исправляем тупой косяк DLE API - такой метод ОБЯЗАН возвращать пустой массив в случае, если ничего не найдено
            if(empty($posts)) $posts = array();
            
            // Второй этап - если в нужном направлении постов не хватило и параметр ring установлен как 1, ищем посты с другой стороны
            if(count($posts) < $this->config['links'] && $this->config['ring'] == 'yes')
            {
                // Создаём список id постов, чтобы отфильтровать их
                $posts_id_array = array();
                foreach($posts as $post)
                {
                    $posts_id_array[] = $post['id'];
                }
                
                // Условие для фильтрации уже отобранных новостей
                if(!empty($posts_id_array))
                {
                    $wheres[] = 'id NOT IN('.implode(',', $posts_id_array).')';
                }
                
                // Складываем условия
                $where = implode(' AND ', $wheres);
                
                // Получаем доп. посты из новых
                $morePosts = $this->dle_api->load_table (PREFIX."_post", '*', $where, true, 0, ($this->config['links'] - count($posts)), 'id', $ordering);
                
                // Исправляем тупой косяк DLE API - такой метод ОБЯЗАН возвращать пустой массив в случае, если ничего не найдено
                if(empty($morePosts)) $morePosts = array();
            
                $posts = array_merge_recursive($posts, $morePosts);
            }
            
            // Формируем список ссылок
            $linksOutput = '';
            foreach($posts as $post)
            {
                // Убираем слэши
                $post['short_story'] = stripslashes($post['short_story']);
                $post['full_story'] = stripslashes($post['full_story']);
                
                // Вывод изображения
                $image = '';
                switch($this->config['image'])
                {
                    // Первое изображение из краткого описания
                    case 'short_story':
                        $image = $this->getContentImage($post['short_story'], 0);
                        break;
                    
                    // Первое изображение из полного описания
                    case 'full_story':
                        $image = $this->getContentImage($post['full_story'], 0);
                        break;
                    
                    // По умолчанию - название дополнительного поля
                    default:
                        $xfields = xfieldsdataload($post['xfields']);
                        if(!empty($xfields) && !empty($xfields[$this->config['image']]))
                        {
                            $image = $xfields[$this->config['image']];
                        }
                        break;
                }
                
                $linksOutput .= $this->applyTemplate('linkenso_link', array(
                    '{link}'        => '<a '.($this->config['title'] != 'empty'?'title="'.($this->config['title'] == 'name'?stripslashes($post['title']):stripslashes($post['metatitle'])).'"':'').' href="'.($this->getPostUrl($post)).'">'.($this->config['anchor'] == 'title'?stripslashes($post['metatitle']):stripslashes($post['title'])).'</a>',
                    '{anchor}'      => $this->config['anchor'] == 'title'?stripslashes($post['metatitle']):stripslashes($post['title']),
                    '{title}'       => $this->config['title'] != 'empty'?($this->config['title'] == 'name'?stripslashes($post['title']):stripslashes($post['metatitle'])):'',
                    '{short-story}' => $this->crobContent($post['short_story'], $this->config['limit']),
                    '{full-story}'  => $this->crobContent($post['short_story'], $this->config['limit']),
                    '{image}'       => $image
                ), array(
                    "'\[link\\](.*?)\[/link\]'si" => '<a '.($this->config['title'] != 'empty'?'title="'.($this->config['title'] == 'name'?stripslashes($post['title']):stripslashes($post['metatitle'])).'"':'').' href="'.($this->getPostUrl($post)).'">'."\\1".'</a>',
                    "'\[show_image\\](.*?)\[/show_image\]'si" => !empty($image)?"\\1":'',
                ));
            }
            
            $output = $this->applyTemplate('linkenso_list', array(
                '{links}' => $linksOutput
            ));
            
            // Если разрешено кэширование, сохраняем в кэш по данной конфигурации
            if($this->dle_api->dle_config['allow_cache'] == 'yes')
            {
                $this->dle_api->save_to_cache('linkenso_'.md5(implode('_', $this->config)), $output);
            }
            
            // Выводим содержимое модуля
            $this->showOutput($output);
        }
        
        
        /*
         * Метод рекурсивно возвращает массив всех подкатегорий определенной категории
         * @param $categoryId - идентификатор исходной категории
         * @return array - массив со списком подкатегорий
         */
        public function getSubcategoriesArray($categoryId)
        {
            // Проверка $categoryId
            $categoryId = intval($categoryId);
            
            // Массив со списком подкатегорий
            $subcategoriesArray = array();
            
            // Получаем список подкатегорий
            $subcategories = $this->dle_api->load_table (PREFIX."_category", 'id', 'parentid = '.$categoryId, true);
            if(empty($subcategories)) $subcategories = array();
            
            foreach($subcategories as $subcategory)
            {
                // Добавляем в массив текущую подкатегорию
                $subcategoriesArray[] = intval($subcategory['id']);
                
                // Добавляем в массив все ее подкатегории
                $subcategoriesArray = array_merge($subcategoriesArray, $this->getSubcategoriesArray($subcategory['id']));
            }
            
            // Возвращаем массив подкатегорий
            return $subcategoriesArray;
        }

        
        /*
         * Метод возвращает массив всех подкатегорий определенной категории
         * @param $categoryId - идентификатор исходной категории
         * @return int - идентификатор самой "верхней" категории этой новости
         */
        public function getGlobalCategory($categoryId)
        {
            // Проверка $categoryId
            $categoryId = intval($categoryId);
            
            // Подхватываем глобальный массив с информацией о категориях
            global $cat_info;
            
            // Ползем по массиву категорий вверх, чтобы получить самую корневую категорию
            while($cat_info[$categoryId]['parentid'] > 0)
            {
                $categoryId = intval($cat_info[$categoryId]['parentid']);
            }
            
            // Возвращаем самую корневую категорию
            return $categoryId;
        }


        /*
         * @param $post - массив с информацией о статье
         * @return string URL для категории
         */
        public function getPostUrl($post)
        {
            if($this->dle_api->dle_config['allow_alt_url'] == 'yes')
            {
                if(
                    ($this->dle_api->dle_config['version_id'] < 9.6 && $post['flag'] && $this->dle_api->dle_config['seo_type'])
                        ||
                    ($this->dle_api->dle_config['version_id'] >= 9.6 && ($this->dle_api->dle_config['seo_type'] == 1 || $this->dle_api->dle_config['seo_type'] == 2))
                )
                {
                    if(intval($post['category']) && $this->dle_api->dle_config['seo_type'] == 2)
                    {
                        $url = $this->dle_api->dle_config['http_home_url'].get_url(intval($post['category'])).'/'.$post['id'].'-'.$post['alt_name'].'.html';
                    }
                    else
                    {
                        $url = $this->dle_api->dle_config['http_home_url'].$post['id'].'-'.$post['alt_name'].'.html';
                    }
                }
                else
                {
                    $url = $this->dle_api->dle_config['http_home_url'].date("Y/m/d/", strtotime($post['date'])).$post['alt_name'].'.html';
                }
            }
            else
            {
                $url = $this->dle_api->dle_config['http_home_url'].'index.php?newsid='.$post['id'];
            }

            return $url;
        }
        
        
        /*
         * Метод обрезает строку $content на длину $length, удаляя все html-теги и возвращает её
         * 
         * @param $content - исходная строка
         * @param $length - количество симолов, на которое нужно обрезать строку
         * 
         * @return string - результат обрезки
         */
        public function crobContent($content = '', $length = 0)
        {
            $content = str_replace("</p><p>", " ", $content);
            $content = strip_tags($content, "<br />");
            $content = trim(str_replace( "<br>", " ", str_replace( "<br />", " ", str_replace( "\n", " ", str_replace( "\r", "", $content)))));

            if($length && dle_strlen($content, $config['charset'] ) > $length )
            {
                $content = dle_substr($content, 0, $length, $this->dle_api->dle_config['charset']);
                if(($temp_dmax = dle_strrpos($content, ' ', $this->dle_api->dle_config['charset'])))
                {
                    $content = dle_substr($content, 0, $temp_dmax, $this->dle_api->dle_config['charset']);
                }
            }
            
            return $content;
        }
        
        
        /*
         * Метод возвращает $index по счету изображение из строки $content
         * 
         * @param $content - строка с контентом для поиска изображения
         * @param $index - порядковый номер возвращаемого изображения начиная с 0
         */
        public function getContentImage($content, $imageIndex = 0)
        {            
            preg_match_all('/(img|src)=("|\')[^"\'>]+/i', $content, $media);
            $data=preg_replace('/(img|src)("|\'|="|=\')(.*)/i',"$3",$media[0]);

            foreach($data as $index => $url)
            {
                if($index == $imageIndex)
                {
                    $info = pathinfo($url);
                    if (isset($info['extension']))
                    {
                        $info['extension'] = strtolower($info['extension']);
                        if (($info['extension'] == 'jpg') || ($info['extension'] == 'jpeg') || ($info['extension'] == 'gif') || ($info['extension'] == 'png'))
                        {
                            return $url;
                        }
                    }   
                }
            }
            
            return false;
        }
        
        
        /*
         * Метод подхватывает tpl-шаблон, заменяет в нём теги и возвращает отформатированную строку
         * @param $template - название шаблона, который нужно применить
         * @param $vars - ассоциативный массив с данными для замены переменных в шаблоне
         * @param $vars - ассоциативный массив с данными для замены блоков в шаблоне
         *
         * @return string tpl-шаблон, заполненный данными из массива $data
         */
        public function applyTemplate($template, $vars = array(), $blocks = array())
        {
            // Подключаем файл шаблона $template.tpl, заполняем его
            $tpl = new dle_template();
            $tpl->dir = TEMPLATE_DIR;
            $tpl->load_template($template.'.tpl');

            // Заполняем шаблон переменными
            foreach($vars as $var => $value)
            {
                $tpl->set($var, $value);
            }

            // Заполняем шаблон блоками
            foreach($blocks as $block => $value)
            {
                $tpl->set_block($block, $value);
            }

            // Компилируем шаблон (что бы это не означало ;))
            $tpl->compile($template);

            // Выводим результат
            return $tpl->result[$template];
        }


        /*
         * Метод выводит содержимое модуля в браузер
         * @param $output - строка для вывода
         */
        public function showOutput($output)
        {
            echo $output;
        }
    }}
    /*---End Of LinkEnso Class---*/
    
    
    // Подхватываем конфигурацию модуля
    $linkEnsoConfig = array(
        'postId'    => !empty($post_id)?$post_id:false,
        'links'     => !empty($links)?$links:3,
        'date'      => !empty($date)?$date:'old',
        'ring'      => !empty($ring)?$ring:'yes',
        'scan'      => !empty($scan)?$scan:'all_cat',
        'anchor'    => !empty($anchor)?$anchor:'name',
        'title'     => !empty($title)?$title:'title',
        'limit'     => !empty($limit)?$limit:0,
        'image'     => !empty($image)?$image:'full_story'
    );
    
    // Создаем экземпляр класса для перелинковки и запускаем его главный метод
    $linkEnso = new LinkEnso($linkEnsoConfig);
    $linkEnso->run();

?>