export interface Errors {
  [key: string]: string[];
}

export const hasError = (errors: Errors, field: string): boolean => {
  return errors.hasOwnProperty(field) && errors[field]?.length > 0;
};

export const clearErrors = (errors: Errors): void => {
  for (const field in errors) {
    errors[field] = [];
  }
};
