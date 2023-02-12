import {Component} from '@angular/core';
import {Validators, AbstractControl, FormBuilder} from '@angular/forms';
import {CredentialService} from '../../services/credential.service';
import {LoginService} from '../../services/login.service';

@Component({
  selector: 'app-login-form',
  templateUrl: './login-form.component.html',
  styleUrls: ['./login-form.component.scss'],
})
export class LoginFormComponent {
  constructor(
    private fb: FormBuilder,
    private loginService: LoginService,
    private credentials: CredentialService) {
  }

  isSubmit: boolean = false;

  loginForm = this.fb.group({
    password: ['', [Validators.required]],
    email: ['', [Validators.required, Validators.email]],
  });

  get formControl(): { [key: string]: AbstractControl } {
    return this.loginForm.controls;
  }

  submit(): void {
    this.isSubmit = true;

    const formValue = this.loginForm.value;
    this.loginService.login({
      email: formValue.email!,
      password: formValue.password!,
    });
  }
}
