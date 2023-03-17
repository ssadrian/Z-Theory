import {HttpClientModule} from '@angular/common/http';
import {NgModule} from '@angular/core';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {BrowserModule} from '@angular/platform-browser';
import {RippleModule} from 'primeng/ripple';

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
import {RankingService} from './services/repository/ranking.service';
import {RegistrationService} from './services/registration.service';
import {RegistrationFormComponent} from './pages/registration-form/registration-form.component';
import {StudentProfileComponent} from './pages/student/student-profile/student-profile.component';
import {TeacherProfileComponent} from './pages/teacher/teacher-profile/teacher-profile.component';
import {StudentService} from './services/repository/student.service';
import {TeacherService} from './services/repository/teacher.service';
import {TableModule} from 'primeng/table';
import {ToastModule} from 'primeng/toast';
import {CalendarModule} from 'primeng/calendar';
import {SliderModule} from 'primeng/slider';
import {MultiSelectModule} from 'primeng/multiselect';
import {ContextMenuModule} from 'primeng/contextmenu';
import {DialogModule} from 'primeng/dialog';
import {ButtonModule} from 'primeng/button';
import {DropdownModule} from 'primeng/dropdown';
import {ProgressBarModule} from 'primeng/progressbar';
import {InputTextModule} from 'primeng/inputtext';
import {CardModule} from 'primeng/card';
import {PasswordModule} from 'primeng/password';
import {MessageService} from 'primeng/api';

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
    HttpClientModule,
    TableModule,
    CalendarModule,
    SliderModule,
    DialogModule,
    MultiSelectModule,
    ContextMenuModule,
    DropdownModule,
    ButtonModule,
    ToastModule,
    InputTextModule,
    ProgressBarModule,
    CardModule,
    PasswordModule,
    RippleModule,
  ],
  providers: [
    LoginService,
    RegistrationService,
    Base64Service,
    StudentService,
    TeacherService,
    RankingService,
    MessageService,
  ],
  bootstrap: [AppComponent],
})
export class AppModule {
}
