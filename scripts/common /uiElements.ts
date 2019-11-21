/**
 * Generate a dismissible warning message.
 * @param msg The message to display.
 */
export const displayWarning = function(msg: string) {
    const containerForm = document.querySelector('.container form');

    const warningDiv = document.createElement('div');
    const warningCloseDiv = document.createElement('div');
    const warningPara = document.createElement('p');
    const warningCloseIcon = document.createElement('i');

    warningDiv.classList.add('warning');
    warningCloseDiv.classList.add('close');
    warningCloseIcon.classList.add('far');
    warningCloseIcon.classList.add('fa-times-circle');

    warningPara.textContent = msg;

    warningCloseDiv.appendChild(warningCloseIcon);
    warningDiv.appendChild(warningCloseDiv);
    warningDiv.appendChild(warningPara);

    if (containerForm !== null) {
        containerForm.appendChild(warningDiv);
    }

    setTimeout(() => {
        warningDiv.style.opacity = '1';
    }, 300);

    warningCloseDiv.onclick = (() => {
        warningDiv.style.opacity = '0';
        setTimeout(() => {
        warningDiv.remove();
        }, 300);
    });
}

/**
 * Checks if an input field is missing and highlights the field is so.
 * @param field The field to check.
 * @returns If the field is missing.
 */
const checkInputField = function checkForMissingField(field: HTMLInputElement | HTMLOptionElement) {
    if (field.value === '') {
      field.classList.add('missing');
      return true;
    } else if (field.classList.contains('missing')) {
      field.classList.remove('missing');
    }
  
    return false;
};
  
/**
 * Checks if a file field is missing a file and highlights the field is so.
 * @param fileField The field to check.
 * @returns If the file field is missing.
 */
const checkFileField = function checkFileMissing(fileField: HTMLInputElement) {
    if (fileField.files !== null && fileField.files.length === 0) {
      fileField.classList.add('missing');
      return true;
    } else if (fileField.classList.contains('missing')) {
      fileField.classList.remove('missing');
    }
  
    return false;
};
  
/**
 * Validates data by checking for missing fields. A field gets highlighted if missing.
 * @param fields The input fields to check.
 * @returns If any of the fields are missing.
 */
export const validateInputFieldData = function validateDataForMissingValues(...fields: Array<HTMLInputElement>) {
    let isfieldMissing = false;

    fields.forEach(field => {
        if (checkInputField(field)) {
            isfieldMissing = true;
        }
    });

    return isfieldMissing;
};

/**
 * Validates data by checking for file fields. A file field gets highlighted if missing.
 * @param fields The file fields to check.
 * @returns If any of the fields are missing.
 */
export const validateFileFieldData = function validateDataForMissingValues(...fields: Array<HTMLInputElement>) {
    let isfieldMissing = false;

    fields.forEach(field => {
        if (checkFileField(field)) {
            isfieldMissing = true;
        }
    });

    return isfieldMissing;
};