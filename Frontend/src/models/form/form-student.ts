import {IStudent} from "../student.model";

export interface IFormStudent extends IStudent {
  password_confirmation: string;
}
