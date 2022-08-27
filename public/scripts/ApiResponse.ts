interface ApiResponseResult
{
    code: number,
    message: string,
}

export interface ApiResponse
{
    result: ApiResponseResult,
    payload: any,
}

export class ApiResponse
{
    public static readonly Ok = 0;
}
