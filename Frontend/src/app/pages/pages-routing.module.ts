import {inject, NgModule, Type} from '@angular/core';
import {CanMatchFn, RouterModule, Routes} from '@angular/router';
import {StudentGuard} from '../guards/student.guard';
import {TeacherGuard} from '../guards/teacher.guard';
import {AssignmentComponent} from './assignment/assignment.component';
import {LoginFormComponent} from './login-form/login-form.component';
import {RegistrationFormComponent} from './registration-form/registration-form.component';
import {StudentProfileComponent} from './student/student-profile/student-profile.component';
import {TeacherProfileComponent} from './teacher/teacher-profile/teacher-profile.component';

function mapToCanMatch(providers: Array<Type<{ canMatch: CanMatchFn }>>): CanMatchFn[] {
  return providers
    .map(provider => (...params) => inject(provider)
      .canMatch(...params));
}

const routes: Routes = [
  {path: 'register', component: RegistrationFormComponent},
  {path: 'login', component: LoginFormComponent},
  {path: 'profile', component: StudentProfileComponent, canMatch: mapToCanMatch([StudentGuard])},
  {path: 'profile', component: TeacherProfileComponent, canMatch: mapToCanMatch([TeacherGuard])},
  {path: 'assignment', component: AssignmentComponent, canMatch: mapToCanMatch([TeacherGuard])}
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class PagesRoutingModule {
}
