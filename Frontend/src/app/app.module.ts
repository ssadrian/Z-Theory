import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { NavbarComponent } from './components/navbar/navbar.component';
import { LogoComponent } from './components/logo/logo.component';
import { FooterComponent } from './components/footer/footer.component';
import { NotFoundComponent } from './pages/not-found/not-found.component';
import { StudentRegisterFormComponent } from './pages/student-register-form/student-register-form.component';
import { TeacherRegisterFormComponent } from './pages/teacher-register-form/teacher-register-form.component';
import { MaterialModule } from './modules/material.module';
import { ReactiveFormsModule } from '@angular/forms';
import { LandPageComponent } from './pages/land-page/land-page.component';
import { RegistrationService } from "./services/registration.service";
import { HttpClientModule } from "@angular/common/http";
import { LoginFormComponent } from './pages/login-form/login-form.component';

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
  providers: [RegistrationService],
  bootstrap: [AppComponent]
})
export class AppModule { }
