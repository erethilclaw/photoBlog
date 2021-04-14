$(document).ready(function() {
    var pageList = new PageList($('.js-page-list')); 
});

class PageList
{
    constructor($element) {
        this.$element = $element;
        this.pages = [];
        this.render();

        this.$element.on('click', '.js-add-page', () => {
            this.addPage();
        });

        $.ajax({
            url: this.$element.data('url')
        }).then(data => {
            this.pages = data;
            this.render();
        })
    }

    addPage() {
       alert('linkat!');
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
        </li>
        `
        });

        this.$element.html(itemsHtml.join(''));
    }
}