import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {StudentGuard} from '../guards/student.guard';
import {TeacherGuard} from '../guards/teacher.guard';
import {LoginFormComponent} from './login-form/login-form.component';
import {RegistrationFormComponent} from './registration-form/registration-form.component';
import {StudentProfileComponent} from './student/student-profile/student-profile.component';
import {TeacherProfileComponent} from './teacher/teacher-profile/teacher-profile.component';

const routes: Routes = [
  { path: 'register', component: RegistrationFormComponent },
  { path: 'login', component: LoginFormComponent },
  { path: 'profile', component: StudentProfileComponent, canMatch: [StudentGuard] },
  { path: 'profile', component: TeacherProfileComponent, canMatch: [TeacherGuard] },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class PagesRoutingModule {}
