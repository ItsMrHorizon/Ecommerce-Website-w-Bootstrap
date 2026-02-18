const modal = document.getElementById('modal');
const addBtn = document.getElementById('addBtn');
const cancelBtn = document.getElementById('cancelBtn');
const form = document.getElementById('productForm');
const searchInput = document.getElementById('searchInput');
const tbody = document.querySelector('#inventoryTable tbody');
let editingRow = null;

// Open modal
addBtn.onclick = () => {
    modal.style.display = 'flex';
    form.reset();
    document.getElementById('modalTitle').textContent = 'Add New Product';
    editingRow = null;
};

// Close modal
cancelBtn.onclick = () => modal.style.display = 'none';
window.onclick = (e) => { if (e.target === modal) modal.style.display = 'none'; };

// Submit form
form.onsubmit = (e) => {
    e.preventDefault();
    const name = document.getElementById('name').value;
    const price = document.getElementById('price').value;
    const category = document.getElementById('category').value;
    const stock = document.getElementById('stock').value;
    const file = document.getElementById('image').files[0];
    const imgSrc = file ? URL.createObjectURL(file) : 'default.png';

    if (editingRow) {
        // Update existing row
        editingRow.cells[2].textContent = name;
        editingRow.cells[3].textContent = price;
        editingRow.cells[4].textContent = category;
        editingRow.cells[5].textContent = stock;
        if (file) editingRow.cells[1].querySelector('img').src = imgSrc;
    } else {
        // Add new row
        const row = tbody.insertRow();
        const id = tbody.rows.length;
        row.innerHTML = `
            <td>${id}</td>
            <td><img src="${imgSrc}" alt=""></td>
            <td>${name}</td>
            <td>${price}</td>
            <td>${category}</td>
            <td>${stock}</td>
            <td>
                <button class="edit">Edit</button>
                <button class="delete">Delete</button>
            </td>
        `;
        row.querySelector('.edit').onclick = () => {
            editingRow = row;
            document.getElementById('modalTitle').textContent = 'Edit Product';
            document.getElementById('name').value = name;
            document.getElementById('price').value = price;
            document.getElementById('category').value = category;
            document.getElementById('stock').value = stock;
            modal.style.display = 'flex';
        };
        row.querySelector('.delete').onclick = () => row.remove();
    }

    modal.style.display = 'none';
};

// Search
searchInput.oninput = () => {
    const term = searchInput.value.toLowerCase();
    tbody.querySelectorAll('tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
    });
};
