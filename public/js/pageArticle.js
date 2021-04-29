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

        this.$button.on('click', (event) => {
            this.handelAddArticle(event);
        });

        this.$element.on('blur', '.js-edit-article-slug', (event) => {
            this.handleEditArticleSlug(event);
        });

        this.$element.on('click', '.js-article-delete', (event) => {
            var getDelMessage = document.querySelector('.js-del-message');
            var delMessage = getDelMessage.dataset.delMessage;
            confirm(delMessage);
            this.handleArticleDelete(event);
        });

        $.ajax({
            url: this.$element.data('url')
        }).then(data => {
            this.articles = data;
            this.render();
        })     
    }

    handelAddArticle(event) {
        const $route = $('#js-add-article');
        var $input = $("#article_slug").val();
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

    handleEditArticleSlug(event){
        const $li = $(event.currentTarget).closest('.list-group-item');
        const id = $li.data('id');
        const article = this.articles.find(article => {
            return article.id === id;
        });
        article.slug = $(event.currentTarget).val();

        $.ajax({
            url: '/admin/page/article/'+id,
            method: 'PUT',
            data: JSON.stringify(article)
        });
    }

    handleArticleDelete(event){
        const $li = $(event.currentTarget).closest('.list-group-item');
        const id = $li.data('id');
        $li.addClass('disabled');

        $.ajax({
            url: '/admin/page/article/'+id,
            method: 'DELETE'
        }).then(() => {
            this.articles = this.articles.filter(article => {
                return article.id !== id;
            });
            this.render();
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