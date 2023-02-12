import {IStudent} from "./student.model";
import {ITeacher} from "./teacher.model";

export interface ILoginResponse {
  user: IStudent | ITeacher;
  role: string,
  token: string
}
