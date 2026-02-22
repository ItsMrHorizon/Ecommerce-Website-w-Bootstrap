 const modal = document.getElementById('modal');

    document.getElementById('addBtn').onclick = () => {
        document.getElementById('modalTitle').textContent = 'Add New Product';
        document.getElementById('formAction').value = 'add';
        document.getElementById('editId').value = '';
        document.getElementById('productForm').reset();
        document.getElementById('imageNote').style.display = 'none';
        modal.style.display = 'flex';
    };

    document.getElementById('cancelBtn').onclick = () => modal.style.display = 'none';

    function editProduct(id, name, price, stock) {
        document.getElementById('modalTitle').textContent = 'Edit Product';
        document.getElementById('formAction').value = 'edit';
        document.getElementById('editId').value = id;
        document.getElementById('name').value = name;
        document.getElementById('price').value = price;
        document.getElementById('stock').value = stock;
        document.getElementById('imageNote').style.display = 'block';
        modal.style.display = 'flex';
    }

    function deleteProduct(id) {
        if (confirm('Delete this product permanently?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `<input type="hidden" name="delete_id" value="${id}">`;
            document.body.appendChild(form);
            form.submit();
        }
    }