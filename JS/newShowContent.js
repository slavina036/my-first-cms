$(function(){
    
    console.log('Привет, это новый js ))');
    new_init_get();
    new_init_post();
});

function new_init_get() 
{
    $('a.newAjaxArticleBodyByGet').click(function(){
        
        var contentId = $(this).attr('data-contentId');
        console.log('li.' + contentId);
        console.log($(this).parents('li.' + contentId));
        let liTag = $(this).parents('li.' + contentId);
        let pTag = liTag.find('p.summary');
        console.log(liTag.find('p.summary'));
        console.log('ID статьи = ', contentId); 
        showLoaderIdentity();
        $.ajax({
            url:'/ajax/newShowContentsHandler.php?articleId=' + contentId,
            dataType: 'text'
        })
        .done (function(obj){
            hideLoaderIdentity();
            console.log('Ответ получен', obj);
            console.log('replaceWith', pTag);
            pTag.text(obj);
        })
        .fail(function(xhr, status, error){
            hideLoaderIdentity();
    
            console.log('ajaxError xhr:', xhr); // выводим значения переменных
            console.log('ajaxError status:', status);
            console.log('ajaxError error:', error);
    
            console.log('Ошибка соединения при получении данных (GET)');
        });
        
        return false;
        
    });  
}

function new_init_post() 
{
    $('a.newAjaxArticleBodyByPost').click(function(){
        var contentId = $(this).attr('data-contentId');
        console.log('newID статьи = ', contentId);
//        console.log('li.' + contentId);
        let liTag = $(this).parents('li.' + contentId);
        let pTag = liTag.find('p.summary');
        showLoaderIdentity();
        $.ajax({
            url:'/ajax/newShowContentsHandler.php',
            dataType: 'json',
//            converters: 'json text',
            data: { articleId: contentId},
            type: "POST",
            method: 'POST'
        })
        .done (function(obj){
            hideLoaderIdentity();
            console.log('Ответ получен', obj.content);
            pTag.text( obj.content );
        })
        .fail(function(xhr, status, error){
            hideLoaderIdentity();
    
    
            console.log('Ошибка соединения с сервером (POST)');
            console.log('ajaxError xhr:', xhr); // выводим значения переменных
            console.log('ajaxError status:', status);
            console.log('ajaxError error:', error);
        });
        
        return false;
        
    });  
}
