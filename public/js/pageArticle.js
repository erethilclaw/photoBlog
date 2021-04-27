$(document).ready(function() {
    document.getElementById("js-add-article").addEventListener("submit", function(event){
        event.preventDefault();
    });
    var articleList = new ArticleList($('.js-article-list'),$('.js-add-article'));   
});

class ArticleList
{
    constructor($element, $button) {
        this.$element = $element;
        this.$button = $button;
        this.articles = [];
        this.render();

        $.ajax({
            url: this.$element.data('url')
        }).then(data => {
            this.articles = data;
            this.render();
        })
        
        this.$button.on('click', (event) => {
            this.handelAddArticle(event);
        });
    }

    handelAddArticle(event) {
        const $route = $('#js-add-article');
        var $input = $("#article_slug").val();
        console.log(JSON.stringify($input));
        $.ajax({
            url: $route.attr('action'),
            method: 'POST',
            data: JSON.stringify($input)
        }).then(() => {
            $.ajax({
                url: this.$element.data('url')
            }).then(data => {
                this.articles = data;
                this.render();
                $("#article_slug").val('');
            })
        });
       
    }

    render() {
        const itemsHtml = this.articles.map(article => {
            return `
            "${article.slug}"
        `
        });

        this.$element.html(itemsHtml.join(''));
    }
}