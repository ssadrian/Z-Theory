import {ITeacher} from "../teacher.model";

export interface IFormTeacher extends ITeacher {
  password_confirmation: string;
}
