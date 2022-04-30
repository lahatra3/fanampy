import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { LoginComponent } from './components/login/login.component';
import { ForgotPasswordComponent } from './components/forgot-password/forgot-password.component';
import { InscrireComponent } from './components/inscrire/inscrire.component';



@NgModule({
  declarations: [
    LoginComponent,
    ForgotPasswordComponent,
    InscrireComponent
  ],
  imports: [
    CommonModule
  ],
  exports: [
    LoginComponent,
    ForgotPasswordComponent,
    InscrireComponent
  ]
})
export class AuthModule { }
