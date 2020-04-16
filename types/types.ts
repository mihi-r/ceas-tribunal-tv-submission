// interface SubmissionInfo {
//     name: string;
//     email: string;
//     org_name: string;
//     description: string;
//     file: string;
// }

export interface APIResponse {
    data: any,
    status: string
}

export interface SubmissionAPIResponse extends APIResponse {
    data: string
}