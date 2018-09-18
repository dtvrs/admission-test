import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

// Components
import {HomeComponent} from './home/home.component';
import {ExerciseComponent} from './exercise/exercise.component';
import {EmployeesComponent} from './employees/employees.component';
import {EmployeeCreateComponent} from './employee-create/employee-create.component';
import {EmployeeEditComponent} from './employee-edit/employee-edit.component';

const routes: Routes = [
    {
        path: '',
        component: HomeComponent
    },
    {
        path: 'exercise',
        component: ExerciseComponent
    },
    {
        path: 'employees',
        component: EmployeesComponent
    },
    {
        path: 'employee/create',
        component: EmployeeCreateComponent
    },
    {
        path: 'employee/:id/edit',
        component: EmployeeEditComponent
    }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
