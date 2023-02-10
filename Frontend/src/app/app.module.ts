import {HttpClientModule} from '@angular/common/http';
import {NgModule} from '@angular/core';
import {ReactiveFormsModule} from '@angular/forms';
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
import {StudentRegisterFormComponent} from './pages/student-register-form/student-register-form.component';
import {TeacherRegisterFormComponent} from './pages/teacher-register-form/teacher-register-form.component';
import {LoginService} from './services/login.service';
import {RegistrationService} from './services/registration.service';

@NgModule({
  declarations: [
    AppComponent,
    NavbarComponent,
    LogoComponent,
    FooterComponent,
    NotFoundComponent,
    StudentRegisterFormComponent,
    TeacherRegisterFormComponent,
    LandPageComponent,
    LoginFormComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    MaterialModule,
    ReactiveFormsModule,
    HttpClientModule,
    ReactiveFormsModule,
  ],
  providers: [LoginService, RegistrationService],
  bootstrap: [AppComponent],
})
export class AppModule {
}
