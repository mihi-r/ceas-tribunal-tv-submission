import { SubmissionData } from './models/submissionRequest';
import { validateInputFieldData, displayWarning} from './common/uiElements';
import { FileConstants } from '../constants/fileConstants';

export const submitData = function(){

    const submitButton = document.querySelector('form #submit-button') as HTMLButtonElement;

    const loader = document.querySelector('.loader') as HTMLDivElement;

    const fileSizeLimit = FileConstants.FILE_SIZE_LIMIT_MB * 1024 * 1024;

    submitButton.onclick = (async() => {
        const name = document.querySelector('form #name') as HTMLInputElement;
        const email = document.querySelector('form #email') as HTMLInputElement;
        const orgName = document.querySelector('form #organization-name') as HTMLInputElement;
        const description = document.querySelector('form #Description') as HTMLInputElement;
        const ad = document.querySelector('form #ad-file') as HTMLInputElement;

        if(!validateInputFieldData(name, email, orgName, description) && ad.files !== null && ad.files.length > 0){
            // Add file checking
            submitButton.style.display = 'none';
            loader.style.display = 'block';

            if (ad.files[0] && ad.files[0].size > fileSizeLimit) {
                displayWarning('Please choose a file under 2MB.');
                submitButton.style.display = 'block';
                loader.style.display = 'none';
            } else {
                const submissionData = new SubmissionData(name.value, email.value, description.value, orgName.value, ad.files[0]);

                try {
                    await submissionData.sendData();
    
                    const introInfo = document.querySelector('.intro-info') as HTMLDivElement;
                    const submissionForm = document.querySelector('form') as HTMLFormElement;
                    const submissionConfirm = document.querySelector('.submission-confirm') as HTMLDivElement;
        
                    introInfo.style.display = 'none';
                    submissionForm.style.display = 'none';
                    submissionConfirm.style.display = 'block';
                } catch (e) {
                    displayWarning(e);
                    submitButton.style.display = 'block';
                    loader.style.display = 'none';
                }
            }
        } else {
            displayWarning('Please complete all fields.')
        }
    });
};

export const watchFileUploadText = function () {
    const fileUploadElement = document.querySelector('#ad-file') as HTMLInputElement;
    const fileUploadText = document.querySelector('.file-text span') as HTMLSpanElement;

    fileUploadElement.onchange = function () {
        if (fileUploadElement.files !== null) {
            const fileName = fileUploadElement.files[0].name;
            fileUploadText.textContent = fileName;
        }
    };
}