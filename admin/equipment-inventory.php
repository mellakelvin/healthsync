<?php
define('IN_ADMIN_PANEL', true);

if (
  !defined('IN_ADMIN_PANEL') &&
  (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest')
) {
  http_response_code(403);
  exit('403 Forbidden: Direct access not allowed.');
}
session_start();
$roleId = $_SESSION['role-id'] ?? null;
?>
<style>
  #inventory-table {
    border-collapse: collapse;
    width: 100%;
  }

  #inventory-table th,
  #inventory-table td {
    padding: 1rem;
    vertical-align: middle;
    background-color: transparent !important;
    border: none;
  }

  #inventory-table thead tr {
    border-bottom: 1px solid #dee2e6;
  }

  #inventory-table tr:hover {
    background-color: #f8f9fa;
  }

  .toast-container {
    position: fixed;
    top: 1rem;
    right: 1rem;
    z-index: 1080;
  }
</style>

<div class="container-fluid px-4 py-4">
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h2 class="mb-0 fw-semibold">Equipments</h2>

    <div class="d-flex align-items-center gap-2 ms-auto">
      <input type="text" class="form-control" id="equipment-search-field" placeholder="Search equipment..." style="width: auto; min-width: 220px;">

      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#equipmentInventoryModal">
        <i class="bi bi-plus-circle me-1"></i> Add Item
      </button>
    </div>
  </div>



  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0" id="inventory-table">
      <thead class="text-secondary fw-semibold">
        <tr>
          <th>Name</th>
          <th>Type</th>
          <th>Stocks</th>
          <th>Status</th>
          <th>Image</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="equipment-table-body"></tbody>
    </table>
  </div>
</div>

<div class="modal fade" id="equipmentInventoryModal" tabindex="-1" aria-labelledby="equipmentInventoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-semibold text-primary" id="equipmentInventoryModalLabel">Add / Edit Equipment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="inventory-form">
          <input type="hidden" id="inventory-id">
          <div class="mb-3">
            <label for="inventory-name" class="form-label">Name</label>
            <input type="text" class="form-control" id="inventory-name" required>
          </div>
          <div class="mb-3">
            <label for="inventory-category" class="form-label">Type</label>
            <input type="text" class="form-control" id="inventory-category" required>
          </div>
          <div class="mb-3">
            <label for="inventory-stocks" class="form-label">Stocks</label>
            <input type="number" class="form-control" id="inventory-stocks" min="0" required>
          </div>
          <div class="mb-3">
            <label for="inventory-image" class="form-label">Image</label>
            <input type="file" class="form-control" id="inventory-image" accept="image/*">
            <p>Max file size: 5MB</p>
          </div>
        </form>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="save-inventory">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="equipmentDeleteConfirmModal" tabindex="-1" aria-labelledby="equipmentDeleteConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="equipmentDeleteConfirmModalLabel">Confirm Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this equipment item?
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirm-delete-btn">Yes, Delete</button>
      </div>
    </div>
  </div>
</div>

<div class="toast-container position-fixed top-0 end-0 p-3"></div>

<script>
  (function() {
    let deleteId = null;

    function showToast(message, type = 'success') {
      const toast = document.createElement('div');
      toast.className = `toast align-items-center text-bg-${type} border-0`;
      toast.setAttribute('role', 'alert');
      toast.setAttribute('aria-live', 'assertive');
      toast.setAttribute('aria-atomic', 'true');

      toast.innerHTML = `
        <div class="d-flex">
          <div class="toast-body">${message}</div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      `;

      document.querySelector('.toast-container').appendChild(toast);
      const bsToast = new bootstrap.Toast(toast, {
        delay: 3000
      });
      bsToast.show();

      toast.addEventListener('hidden.bs.toast', () => toast.remove());
    }

    function fetchInventory() {
      axios.get('/healthsync/api/inventory/equipments.php')
        .then(res => {
          const tableBody = $('#equipment-table-body').empty();

          (res.data || []).forEach(item => {
            let badge = '';
            let status = '';

            if (item.stocks == 0) {
              badge = 'danger';
              status = 'Out of Stock';
            } else if (item.stocks > 10) {
              badge = 'success';
              status = 'Available';
            } else {
              badge = 'warning text-dark';
              status = 'Low';
            }

            const tr = $('<tr></tr>');
            tr.append(`<td><p class="mb-0 text-truncate" style="max-width:150px">${item.name}</p></td>`);
            tr.append(`<td>${item.type}</td>`);
            tr.append(`<td>${item.stocks}</td>`);
            tr.append(`<td><span class="badge bg-${badge}">${status}</span></td>`);
            item.image ? tr.append(`<td><a href="${item.image}" target="_blank">Image</a></td>`) : tr.append(`<td>No Image</td>`);
            tr.append(`<td>${item.created_at}</td>`);
            const actions = $('<td></td>');
            const btnGroup = $(`
              <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary edit-btn" data-id="${item.id}">Edit</button>
                <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${item.id}">Delete</button>
              </div>
            `);
            actions.append(btnGroup);
            tr.append(actions);
            tableBody.append(tr);
          });
        })
        .catch(() => showToast('Failed to load equipment.', 'danger'));
    }

    function clearForm() {
      $('#inventory-id').val('');
      $('#inventory-form')[0].reset();
    }

    $(document).ready(function() {
      fetchInventory();

      $('#equipmentInventoryModal').on('hidden.bs.modal', clearForm);

      $('#save-inventory').click(function() {
        const id = $('#inventory-id').val();
        const formData = new FormData();
        formData.append('name', $('#inventory-name').val());
        formData.append('type', $('#inventory-category').val());
        formData.append('stocks', $('#inventory-stocks').val());

        const fileInput = document.getElementById('inventory-image');
        if (fileInput.files.length > 0) {
          formData.append('image', fileInput.files[0]);
        }

        if (id) formData.append('id', id);
        formData.append('action', id ? 'update' : 'create');

        axios.post('/healthsync/api/inventory/equipments.php', formData)
          .then(() => {
            $('#equipmentInventoryModal').modal('hide');
            fetchInventory();
            showToast(id ? 'Equipment updated successfully!' : 'Equipment added successfully!');
          })
          .catch(() => showToast('Error saving equipment.', 'danger'));
      });

      $(document).on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        axios.get(`/healthsync/api/inventory/equipments.php?id=${id}`)
          .then(res => {
            const item = res.data;
            $('#inventory-id').val(item.id);
            $('#inventory-name').val(item.name);
            $('#inventory-category').val(item.type);
            $('#inventory-stocks').val(item.stocks);
            $('#equipmentInventoryModal').modal('show');
          });
      });

      $(document).on('click', '.delete-btn', function() {
        deleteId = $(this).data('id');
        $('#equipmentDeleteConfirmModal').modal('show');
      });

      $('#confirm-delete-btn').click(function() {
        axios.delete('/healthsync/api/inventory/equipments.php', {
          data: {
            id: deleteId
          }
        }).then(() => {
          $('#equipmentDeleteConfirmModal').modal('hide');
          fetchInventory();
          showToast('Equipment deleted successfully!');
        }).catch(() => {
          $('#equipmentDeleteConfirmModal').modal('hide');
          showToast('Failed to delete equipment.', 'danger');
        });
      });
    });

    $('#equipment-search-field').on('input', function() {
      const query = $(this).val().toLowerCase();
      $('#equipment-table-body tr').each(function() {
        const rowText = $(this).text().toLowerCase();
        $(this).toggle(rowText.includes(query));
      });
    });

  })();
</script>