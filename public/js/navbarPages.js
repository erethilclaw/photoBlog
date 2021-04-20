$(document).ready(function() {
    var getDelMessage = document.querySelector('.js-del-message');
    var delMessage = getDelMessage.dataset.delMessage;
    console.log(delMessage);


    var pageList = new PageList($('.js-page-list'));   
});

class PageList
{
    constructor($element) {
        this.$element = $element;
        this.pages = [];
        this.render();

        this.$element.on('click', '.js-page-delete', () => {
            confirm(delMessage);
        });

        $.ajax({
            url: this.$element.data('url')
        }).then(data => {
            this.pages = data;
            this.render();
        })
    }

    addPage(event){
        alert('add page!');
        this.render();
    }

    render() {
        const itemsHtml = this.pages.map(page => {
            return `
            <li class="list-group-item d-flex justify-content-between align-items-center" data-id="${page.id}">
            <span class="drag-handle fa fa-reorder"></span>
            <input type="text" value="${page.slug}" class="form-control js-edit-filename" style="width: auto;">
        
            <span>
                <button class="js-reference-delete btn btn-link btn-sm"><span class="fa fa-trash"></span></button>
            </span>

            <a href="/admin/editPage/${page.id}">
                <span class="glyphicon glyphicon-pencil"></span>
            </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <a href="/admin/delPage/${page.id}" class="js-page-delete" onclick="return confirm(delMessage)"><span
                                            class="glyphicon glyphicon-trash text-red"></span></a>
        </li>
        `
        });

        this.$element.html(itemsHtml.join(''));
    }
}