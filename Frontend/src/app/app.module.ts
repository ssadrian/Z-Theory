import {HttpClientModule} from '@angular/common/http';
import {NgModule} from '@angular/core';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {BrowserModule} from '@angular/platform-browser';

import {AppRoutingModule} from './app-routing.module';
import {AppComponent} from './app.component';
import {FooterComponent} from './components/footer/footer.component';
import {LogoComponent} from './components/logo/logo.component';
import {NavbarComponent} from './components/navbar/navbar.component';
import {MaterialModule} from './modules/material.module';
import {LandPageComponent} from './pages/land-page/land-page.component';
import {LoginFormComponent} from './pages/login-form/login-form.component';
import {NotFoundComponent} from './pages/not-found/not-found.component';
import {Base64Service} from './services/base64.service';
import {LoginService} from './services/login.service';
import {RegistrationService} from './services/registration.service';
import {RegistrationFormComponent} from './pages/registration-form/registration-form.component';
import {StudentProfileComponent} from './pages/student/student-profile/student-profile.component';
import {TeacherProfileComponent} from './pages/teacher/teacher-profile/teacher-profile.component';

@NgModule({
  declarations: [
    AppComponent,
    NavbarComponent,
    LogoComponent,
    FooterComponent,
    NotFoundComponent,
    LandPageComponent,
    LoginFormComponent,
    StudentProfileComponent,
    TeacherProfileComponent,
    RegistrationFormComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    MaterialModule,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule
  ],
  providers: [LoginService, RegistrationService, Base64Service],
  bootstrap: [AppComponent],
})
export class AppModule {
}
