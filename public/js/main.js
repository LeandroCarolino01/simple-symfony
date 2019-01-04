const articles = document.getElementById('articles');

if (articles) {
    articles.addEventListener('click', e => {
        if (e.target.className === 'btn btn-danger delete-article') {
            if (confirm('Are you sure?')) {
                const id = e.target.getAttribute('data-id');

                fetch(`/article/delete/${id}`, {
                    method: 'DELETE'
                }).then(res => window.location.reload());
            }
        }
    });
}

// Rijk made this
var likeButtons = $('.like-button');

likeButtons.on('click', function(){
   var likeStatus = $(this).attr('id');
   var articleId = $(this).data('id');

   var postData = {liked: likeStatus};
   $.ajax({
       type: "POST",
       url: '/article/like/' + articleId,
       data: postData,
       success: function() {
           location.reload();
       }
    });
});

