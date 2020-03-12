import { SubmissionData } from './models/submissionRequest';
import { validateInputFieldData, displayWarning} from './common/uiElements';
export const submitData = function(){
    const submitButton = document.querySelector('form #submit-button') as HTMLButtonElement;
    console.log(submitButton);
    submitButton.onclick = (async() => {
        const name = document.querySelector('form #name') as HTMLInputElement;
        const email = document.querySelector('form #email') as HTMLInputElement;
        const orgName = document.querySelector('form #organization-name') as HTMLInputElement;
        const description = document.querySelector('form #Description') as HTMLInputElement;

        if(!validateInputFieldData(name,email,orgName,description)){
            const submissionData = new SubmissionData(name.value, email.value,orgName.value, description.value, '' );

            const introInfo = document.querySelector('.intro-info') as HTMLDivElement;
            const submissionForm =document.querySelector('form') as HTMLFormElement;
            const submissionConfirm = document.querySelector('.submission-confirm') as HTMLDivElement;

            introInfo.style.display = 'none';
            submissionForm.style.display = 'none';
            submissionConfirm.style.display = 'block';



            try {
                await submissionData.sendData();
            } catch (e) {
                displayWarning(e);
            }
        } else {
            displayWarning('Test - validation error')
        }
    });

};