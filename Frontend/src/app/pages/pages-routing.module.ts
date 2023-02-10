import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LoginFormComponent } from './login-form/login-form.component';
import { StudentRegisterFormComponent } from './student/student-register-form/student-register-form.component';
import { TeacherRegisterFormComponent } from './teacher/teacher-register-form/teacher-register-form.component';

const routes: Routes = [
  { path: 'register-student', component: StudentRegisterFormComponent },
  { path: 'register-teacher', component: TeacherRegisterFormComponent },
  { path: 'login', component: LoginFormComponent },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class PagesRoutingModule {}
