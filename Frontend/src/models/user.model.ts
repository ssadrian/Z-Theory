export interface IUser {
  id: number;
  nickname: string;
  email: string;
  password: string;
  name: string;
  surnames: string;
  avatar: string;
  role: 'teacher' | 'student';
}
