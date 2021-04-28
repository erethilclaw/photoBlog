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
        this.sortable = Sortable.create(this.$element[0], {
            handle: '.drag-handle',
            animation: 150,
            onEnd: () => {
                $.ajax({
                    url: this.$element.data('url')+'/reorder',
                    method: 'POST',
                    data: JSON.stringify(this.sortable.toArray())
                })
            }
        });
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
            <li class="list-group-item d-flex justify-content-between align-items-center" data-id="${article.id}">
            <span class="drag-handle fa fa-reorder"></span>
            <input type="text" value="${article.slug}" class="form-control js-edit-article-slug" style="width: auto;">
            

            <a href="/admin/editArticle/${article.id}">
                <span class="glyphicon glyphicon-pencil"></span>
            </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <a class="js-article-delete" ><span
                                            class="glyphicon glyphicon-trash text-red"></span></a>
        </li>
        `
        });

        this.$element.html(itemsHtml.join(''));
    }
}