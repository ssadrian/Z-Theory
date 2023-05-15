import { IStudent } from './student.model';
import { ITeacher } from './teacher.model';

export interface ISessionCookie {
  email: string;
  password: string;
  token: string;
  role: 'student' | 'teacher' | '';
  currentUser?: IStudent | ITeacher;
}
