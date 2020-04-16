import { SubmissionAPIResponse } from '../../types/types';
import { StatusConstants } from '../../constants/statusConstants';

export class SubmissionData {
    public name: string;
    public email: string;
    public description: string;
    public org_name: string;
    public file: File; 

    /**
    The constructor
    *Full name of the the person submitting
    *email of the person 
    * description 
    *Organization name _ enter the name of your organization
    *Upload file = file
    **/

    constructor(
        name: string, email: string, description: string, org_name: string, file: File

    ) {
        this.name = name;
        this.email = email;
        this.description = description;
        this.org_name = org_name;
        this.file = file;
    }

    public async sendData() {
        const submissionFormData = new FormData();

        submissionFormData.append('nameText', this.name);
        submissionFormData.append('emailText', this.email);
        submissionFormData.append('descriptionText', this.description);
        submissionFormData.append('org_nameText', this.org_name);
        submissionFormData.append('imageFile', this.file);

        const response = await fetch('../api/send_submission.php', {
            method: 'POST',
            body: submissionFormData
        });

        const data: SubmissionAPIResponse = await response.json();

        if (data.status === StatusConstants.ERROR) {
            throw new Error(data.data);
        }
    }
}
