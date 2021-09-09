
function deleteBook(id){
    if (confirm('Мэдээллийг устгахдаа итгэлтэй байна уу?')) {
        fetch(`/book/delete/${id}`, {
        method: 'DELETE'
        }).then(res => window.location.reload());
    }
}