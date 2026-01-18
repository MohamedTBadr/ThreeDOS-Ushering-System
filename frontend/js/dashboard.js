// Configuration
const API_URL = '../backend/api.php';
const TOKEN = localStorage.getItem('usher_token');

// Redirect if not logged in
if (!TOKEN) window.location.href = 'login.html';

// State
let currentPage = 1;
let totalPages = 1;
let userRole = localStorage.getItem('user_role');
let currentFilters = {
    search: '',
    level: '',
    rating: ''
};

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('.logo h2').innerText = `${localStorage.getItem('user_council')} Admin`;
    loadApplicants();
    setupEventListeners();
});

// Setup Event Listeners
function setupEventListeners() {
    let searchTimeout;
    document.getElementById('search').addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentFilters.search = e.target.value;
            currentPage = 1;
            loadApplicants();
        }, 500);
    });
    
    document.getElementById('level-filter').addEventListener('change', (e) => {
        currentFilters.level = e.target.value;
        currentPage = 1;
        loadApplicants();
    });
    
    document.getElementById('rating-filter').addEventListener('change', (e) => {
        currentFilters.rating = e.target.value;
        currentPage = 1;
        loadApplicants();
    });
    
    document.getElementById('edit-form').addEventListener('submit', handleEditSubmit);
}

// Load Applicants
async function loadApplicants() {
    const grid = document.getElementById('applicants-grid');
    grid.innerHTML = '<div class="loading">Loading applicants...</div>';
    
    try {
        let url = `${API_URL}?page=${currentPage}`;
        if (currentFilters.search) url += `&search=${encodeURIComponent(currentFilters.search)}`;
        if (currentFilters.level) url += `&level=${encodeURIComponent(currentFilters.level)}`;
        if (currentFilters.rating) url += `&rating=${encodeURIComponent(currentFilters.rating)}`;
        
        const response = await fetch(url, {
            headers: { 'Authorization': `Bearer ${TOKEN}` }
        });
        const result = await response.json();
        
        if (result.status === 'success') {
            displayApplicants(result.data.applicants);
            updatePagination(result.data.pagination);
            updateStatistics(result.data.applicants); // Local stats for simpler data
        } else {
            if (response.status === 401) window.location.href = 'login.html';
            showToast(result.message, 'error');
        }
    } catch (error) {
        showToast('Failed to load data', 'error');
    }
}

// Display Applicants
function displayApplicants(applicants) {
    const grid = document.getElementById('applicants-grid');
    
    if (applicants.length === 0) {
        grid.innerHTML = '<div class="empty-state">No applicants found</div>';
        return;
    }
    
    grid.innerHTML = applicants.map(applicant => `
        <div class="applicant-card">
            <div class="card-header">
                <div class="card-info">
                    <h3>${escapeHtml(applicant.name)}</h3>
                    <p>${escapeHtml(applicant.email)}</p>
                </div>
                <span class="rating-badge rating-${getRatingClass(applicant.rating)}">
                    ${applicant.rating || 'Pending'}
                </span>
            </div>
            
            <div class="card-details">
                <div class="detail-item"><span class="icon">📞</span><span>${escapeHtml(applicant.phone)}</span></div>
                <div class="detail-item"><span class="icon">🏫</span><span>${escapeHtml(applicant.college)}</span></div>
                <div class="detail-item"><span class="icon">📚</span><span>${escapeHtml(applicant.level)}</span></div>
            </div>
            
            ${applicant.preferences ? `<div class="card-preferences"><strong>Pref:</strong> ${escapeHtml(applicant.preferences)}</div>` : ''}
            ${applicant.notes ? `<div class="card-preferences"><strong>Notes:</strong> ${escapeHtml(applicant.notes)}</div>` : ''}
            
            <div class="card-actions">
                <button class="btn-edit" onclick="openEditModal(${applicant.id})">✏️ Edit</button>
                ${userRole !== 'Instructor' ? `<button class="btn-delete" onclick="deleteApplicant(${applicant.id})">🗑️ Delete</button>` : ''}
            </div>
        </div>
    `).join('');
}

// Update Statistics (Helper)
function updateStatistics(applicants) {
    const stats = { total: applicants.length, accepted: 0, backup: 0, rejected: 0, pending: 0 };
    applicants.forEach(app => {
        const r = app.rating || 'Pending';
        if (r === 'Acceptance') stats.accepted++;
        else if (r === 'B') stats.backup++;
        else if (r === 'Rejection') stats.rejected++;
        else stats.pending++;
    });
    document.getElementById('stat-total').innerText = stats.total;
    document.getElementById('stat-accepted').innerText = stats.accepted;
    document.getElementById('stat-backup').innerText = stats.backup;
    document.getElementById('stat-rejected').innerText = stats.rejected;
    document.getElementById('stat-pending').innerText = stats.pending;
}

// Open Edit Modal
async function openEditModal(id) {
    try {
        const response = await fetch(`${API_URL}`, {
            headers: { 'Authorization': `Bearer ${TOKEN}` }
        });
        const result = await response.json();
        const applicant = result.data.applicants.find(a => a.id == id);
        
        if (applicant) {
            document.getElementById('edit-id').value = applicant.id;
            document.getElementById('edit-name').value = applicant.name;
            document.getElementById('edit-email').value = applicant.email;
            document.getElementById('edit-phone').value = applicant.phone;
            document.getElementById('edit-college').value = applicant.college;
            document.getElementById('edit-level').value = applicant.level;
            document.getElementById('edit-preferences').value = applicant.preferences || '';
            document.getElementById('edit-rating').value = applicant.rating || 'Pending';
            document.getElementById('edit-notes').value = applicant.notes || '';
            
            // Apply Least Privilege UI
            const isInstructor = userRole === 'Instructor';
            const disableFields = ['name', 'email', 'phone', 'college', 'level', 'preferences'];
            disableFields.forEach(f => {
                document.getElementById(`edit-${f}`).disabled = isInstructor;
            });

            document.getElementById('edit-modal').classList.add('active');
        }
    } catch (error) {
        showToast('Error opening modal', 'error');
    }
}

// Close Modal
function closeModal() {
    document.getElementById('edit-modal').classList.remove('active');
    document.getElementById('edit-form').reset();
}

// Handle Edit Submit
async function handleEditSubmit(e) {
    e.preventDefault();
    
    const data = {
        id: parseInt(document.getElementById('edit-id').value),
        name: document.getElementById('edit-name').value,
        email: document.getElementById('edit-email').value,
        phone: document.getElementById('edit-phone').value,
        college: document.getElementById('edit-college').value,
        level: document.getElementById('edit-level').value,
        preferences: document.getElementById('edit-preferences').value,
        rating: document.getElementById('edit-rating').value,
        notes: document.getElementById('edit-notes').value
    };
    
    try {
        const response = await fetch(API_URL, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${TOKEN}`
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        if (result.status === 'success') {
            showToast('Updated successfully', 'success');
            closeModal();
            loadApplicants();
        } else {
            showToast(result.message, 'error');
        }
    } catch (error) {
        showToast('Failed to update', 'error');
    }
}

// Delete Applicant
async function deleteApplicant(id) {
    if (!confirm('Are you sure?')) return;
    
    try {
        const response = await fetch(API_URL, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${TOKEN}`
            },
            body: JSON.stringify({ id })
        });
        
        const result = await response.json();
        if (result.status === 'success') {
            showToast('Deleted', 'success');
            loadApplicants();
        } else {
            showToast(result.message, 'error');
        }
    } catch (error) {
        showToast('Failed to delete', 'error');
    }
}

// Pagination Functions
function nextPage() {
    if (currentPage < totalPages) {
        currentPage++;
        loadApplicants();
    }
}

function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        loadApplicants();
    }
}

// Clear Filters
function clearFilters() {
    currentFilters = {
        search: '',
        level: '',
        rating: ''
    };
    
    document.getElementById('search').value = '';
    document.getElementById('level-filter').value = '';
    document.getElementById('rating-filter').value = '';
    
    currentPage = 1;
    loadApplicants();
}

// Helper Functions
function getRatingClass(rating) {
    const ratingMap = {
        'Pending': 'pending',
        'Acceptance': 'acceptance',
        'B': 'b',
        'Rejection': 'rejection'
    };
    return ratingMap[rating] || 'pending';
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function showToast(message, type = 'info') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `toast ${type} show`;
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

// Close modal when clicking outside
document.addEventListener('click', (e) => {
    const modal = document.getElementById('edit-modal');
    if (e.target === modal) {
        closeModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeModal();
    }
});
