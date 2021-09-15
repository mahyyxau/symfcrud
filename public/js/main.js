
function deleteBook(id){
    if (confirm('Мэдээллийг устгахдаа итгэлтэй байна уу?')) {
        fetch(`/book/delete/${id}`, {
        method: 'DELETE'
        }).then(res => window.location.reload());
    }
}

function deleteCategory(id){
    if (confirm('Мэдээллийг устгахдаа итгэлтэй байна уу?')) {
        fetch(`/categories/delete/${id}`, {
        method: 'DELETE'
        }).then(res => window.location.reload());
    }
}

function deleteAuthor(id){
    if (confirm('Мэдээллийг устгахдаа итгэлтэй байна уу?')) {
        fetch(`/authors/delete/${id}`, {
        method: 'DELETE'
        }).then(res => window.location.reload());
    }
}

$(document).ready(function() {
    $('.js-datepicker').datepicker({
        format: 'yyyy-mm-dd'
    });
});