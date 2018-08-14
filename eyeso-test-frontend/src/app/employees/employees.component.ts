import { Component, OnInit } from '@angular/core';
import {ApiService} from '../api.service';

@Component({
  selector: 'app-employees',
  templateUrl: './employees.component.html',
  styleUrls: ['./employees.component.scss']
})
export class EmployeesComponent implements OnInit {
  public employees;
  loading = true;

  constructor(private _apiService: ApiService) { }

  ngOnInit() {
      this.getEmployees();
  }

  getEmployees() {
      this._apiService.getEmployees().subscribe(
          data => { this.employees = data; },
          err => console.error(err),
          () => { this.loading = false; console.log('Employees loaded successfully'); }
      );
  }

}
