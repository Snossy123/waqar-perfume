document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('type');
    const optionsContainer = document.getElementById('options-container');
    const optionsList = document.getElementById('options-list');
    const addOptionBtn = document.getElementById('add-option');

    function handleRemoveOption(button) {
        const optionRow = button.closest('.option-row');
        const allRows = document.querySelectorAll('.option-row');
        
        if (allRows.length > 1) {
            optionRow.remove();
        } else {
            optionRow.querySelector('input').value = '';
        }
    }

    function setupRemoveListeners() {
        document.querySelectorAll('.remove-option').forEach(button => {
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);
            
            newButton.addEventListener('click', function() {
                handleRemoveOption(this);
            });
        });
    }

    function toggleOptions() {
        if (['select', 'radio', 'checkbox'].includes(typeSelect.value)) {
            optionsContainer.style.display = 'block';
        } else {
            optionsContainer.style.display = 'none';
        }
    }

    function addOption(value = '') {
        const optionRow = document.createElement('div');
        optionRow.className = 'input-group mb-2 option-row';
        optionRow.innerHTML = `
            <input type="text" class="form-control" name="options[]" value="${value}" required>
            <button type="button" class="btn btn-outline-danger remove-option" data-bs-toggle="tooltip" title="Remove option">
                <i class="bi bi-x-lg"></i>
            </button>
        `;
        optionsList.appendChild(optionRow);
        
        const removeBtn = optionRow.querySelector('.remove-option');
        new bootstrap.Tooltip(removeBtn);
        
        removeBtn.addEventListener('click', function() {
            handleRemoveOption(this);
        });
    }
    typeSelect.addEventListener('change', toggleOptions);
    
    addOptionBtn.addEventListener('click', function() {
        addOption();
    });

    toggleOptions();
    
    setupRemoveListeners();
    
    if (document.querySelectorAll('.option-row').length === 0) {
        addOption();
    }
});