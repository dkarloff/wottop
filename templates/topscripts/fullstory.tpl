{poll}
<div class="post">
  <h1 class="comen"><h1>{title} </h1><span style="float:right;margin-top:5px;">{favorites}</span></h1> 
  
    <div class="postmetadata">
    <span class="date">[edit][X][/edit]</span> 
    <span class="date" >Автор:  {author} </span> 
    <span class="date" >Категория:  {link-category} </span>
    <span class="rate" >{rating}</span> 
  </div>   
  <div class="text">{short-story}{full-story}
			<div class="clr"></div>
			[edit-date]<p class="editdate"><br /><i>Новость отредактировал: <b>{editor}</b> - {edit-date}
			<br />[edit-reason]Причина: {edit-reason}[/edit-reason]</i></p>[/edit-date]</div><!-- End text --> 
</div>
<h2 class="comen">Комментарии к статье:</h2>
<h1 class="comen">{title} </h1>
{addcomments}
[related-news]<h2 class="comen">  Другие моды, прицелы, шкурки для World of Tanks: </h2>
<ul>{include file='engine/modules/linkenso.php?post_id={news-id}&links=5&date=old&ring=yes&scan=same_cat&anchor=name&title=title&image=short_story&limit=200'} </ul>
  <br />