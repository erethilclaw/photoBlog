$(document).ready(function() {
    var articleList = new ArticleList($('.js-article-list'));   
});

class ArticleList
{
    constructor($element) {
        this.$element = $element;
        this.articles = [];
        this.render();

        $.ajax({
            url: this.$element.data('url')
        }).then(data => {
            this.articles = data;
            this.render();
        })
        console.log(this.articles);
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