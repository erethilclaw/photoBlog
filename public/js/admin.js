Dropzone.autoDiscover = false;

$(document).ready(function() {
    var referenceList = new ReferenceList($('.js-reference-list'));

    initializeDropzone(referenceList);
});

// todo - use Webpack Encore so ES6 syntax is transpiled to ES5
class ReferenceList
{
    constructor($element) {
        this.$element = $element;
        this.references = [];
        this.render();

        $.ajax({
            url: this.$element.data('url')
        }).then(data => {
            this.references = data;
            this.render();
        })
    }

    addReference(reference) {
        this.references.push(reference);
        this.render();
    }
    render() {
        const itemsHtml = this.references.map(reference => {
            return `
<li class="list-group-item d-flex justify-content-between align-items-center" data-id="${reference.id}">
    <span class="drag-handle fa fa-reorder"></span>
    <input type="text" value="${reference.originalFilename}" class="form-control js-edit-filename" style="width: auto;">

    <span>
        <a href="/admin/article/references/${reference.id}/download" class="btn btn-link btn-sm"><span class="fa fa-download" style="vertical-align: middle"></span></a>
        <button class="js-reference-delete btn btn-link btn-sm"><span class="fa fa-trash"></span></button>
    </span>
</li>
`
        });

        this.$element.html(itemsHtml.join(''));
    }
}
/**
 * @param {ReferenceList} referenceList
 */
function initializeDropzone(referenceList) {
    var formElement = document.querySelector('.js-reference-dropzone');
    if (!formElement){
        return;
    }

    var dropzone = new Dropzone(formElement, {
        paramName: 'reference',
        init: function() {
            this.on('success', function(file, data) {
                referenceList.addReference(data);
            });

            this.on('error', function (file, data) {
                if (data.detail) {
                    this.emit('error', file, data.detail);
                }
            });
        }
    });
}
