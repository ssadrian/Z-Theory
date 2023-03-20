import {ITeacher} from './teacher.model';

export interface IAssignment {
  id: number;
  title: string;
  description: string;
  content: string;
  points: number;
  creator: ITeacher;
}
