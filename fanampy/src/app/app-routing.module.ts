import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LandingComponent } from './landing/components/landing/landing.component';
import { NotFoundComponent } from './not-found/not-found.component';

const routes: Routes = [
  { path: 'fanampy', component: LandingComponent },
  { path: '', redirectTo: 'fanampy', pathMatch: 'full' },
  { path: '**', component: NotFoundComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
