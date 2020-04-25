import { SubmissionAPIResponse } from '../../types/types';
import { StatusConstants } from '../../constants/statusConstants';

export class SubmissionData {
    public name: string;
    public email: string;
    public description: string;
    public orgName: string;
    public file: File; 

    /**
     * The constructor:
     * @param name The Full names of the the person submitting.
     * @param email The email of the person.
     * @param description The description.
     * @param orgName The name of the orgainzation submitting the ad.
     * @param file File being submitted.
     */
    constructor(
        name: string, email: string, description: string, orgName: string, file: File
    ) {
        this.name = name;
        this.email = email;
        this.description = description;
        this.orgName = orgName;
        this.file = file;
    }

    /**
     * Sends data to submit the ad. 
     */
    public async sendData() {
        const submissionFormData = new FormData();

        submissionFormData.append('nameText', this.name);
        submissionFormData.append('emailText', this.email);
        submissionFormData.append('descriptionText', this.description);
        submissionFormData.append('orgNameText', this.orgName);
        submissionFormData.append('imageFile', this.file);

        const response = await fetch('../api/send_submission.php', {
            method: 'POST',
            body: submissionFormData
        });

        const data: SubmissionAPIResponse = await response.json();

        if (data.status === StatusConstants.ERROR) {
            throw new Error(data.message);
        }
    }
}
