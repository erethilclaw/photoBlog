$(document).ready(function() {
    var pageList = new PageList($('.js-page-list'));   
});

class PageList
{
    constructor($element) {
        this.$element = $element;
        this.pages = [];
        this.render();

        this.$element.on('click', '.js-page-delete', (event) => {
            var getDelMessage = document.querySelector('.js-del-message');
            var delMessage = getDelMessage.dataset.delMessage;
            confirm(delMessage);
            this.handlePageDelete(event);
        });

        this.$element.on('blur', '.js-edit-pageslug', (event) => {
            this.handlePageEditSlug(event);
        });

        $.ajax({
            url: this.$element.data('url')
        }).then(data => {
            this.pages = data;
            this.render();
        })
    }

    handlePageDelete(event) {
        const $li = $(event.currentTarget).closest('.list-group-item');
        const id = $li.data('id');
        $li.addClass('disabled');

        $.ajax({
            url: '/admin/delPage/'+id,
            method: 'DELETE'
        }).then(() => {
            this.pages = this.pages.filter(page => {
                return page.id !== id;
            });
            this.render();
        });
    }

    handlePageEditSlug(event) {
        const $li = $(event.currentTarget).closest('.list-group-item');
        const id = $li.data('id');
        const page = this.pages.find(page => {
            return page.id === id;
        });
        page.slug = $(event.currentTarget).val();
        console.log(page);

        $.ajax({
            url: '/admin/editPageSlug/'+id,
            method: 'PUT',
            data: JSON.stringify(page)
        });
    }

    render() {
        const itemsHtml = this.pages.map(page => {
            return `
            <li class="list-group-item d-flex justify-content-between align-items-center" data-id="${page.id}">
            <span class="drag-handle fa fa-reorder"></span>
            <input type="text" value="${page.slug}" class="form-control js-edit-pageslug" style="width: auto;">
            

            <a href="/admin/editPage/${page.id}">
                <span class="glyphicon glyphicon-pencil"></span>
            </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <a class="js-page-delete" ><span
                                            class="glyphicon glyphicon-trash text-red"></span></a>
        </li>
        `
        });

        this.$element.html(itemsHtml.join(''));
    }
}