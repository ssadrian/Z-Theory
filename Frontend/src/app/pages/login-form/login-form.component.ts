import { Component } from '@angular/core';
import { AbstractControl, FormBuilder, Validators } from '@angular/forms';
import { LoginService } from '../../services/login.service';
import { CredentialService } from 'src/app/services/credential.service';
import { CookieService } from 'ngx-cookie-service';
import { environment } from 'src/app/environments/environment';

@Component({
  selector: 'app-login-form',
  templateUrl: './login-form.component.html',
  styleUrls: ['./login-form.component.scss'],
})
export class LoginFormComponent {
  constructor(
    private fb: FormBuilder,
    private loginService: LoginService,
    private credentialService: CredentialService,
    private cookieService: CookieService
  ) {
    const cookieName: string = environment.sessionCookieName;

    
  }

  isSubmit: boolean = false;

  loginForm = this.fb.group({
    password: ['', [Validators.required]],
    email: ['', [Validators.required, Validators.email]],
    rememberMe: [false],
  });

  get formControl(): { [key: string]: AbstractControl } {
    return this.loginForm.controls;
  }

  submit(): void {
    this.isSubmit = true;

    const formValue = this.loginForm.value;
    this.loginService.login(
      {
        email: formValue.email!,
        password: formValue.password!,
      },
      formValue.rememberMe!
    );
  }
}
