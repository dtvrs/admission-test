import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import {Observable} from 'rxjs/Observable';
import { Employee } from './employee';

const httpOptions = {
    headers: new HttpHeaders({ 'Content-Type': 'application/json' })
};

@Injectable()
export class ApiService {

  constructor(private http: HttpClient) { }

  getEmployees() {
        return this.http.get('api/employees/list')
  }

  getJobs() {
    return this.http.get('api/job/')
  }

  createEmployee(employee: Employee) {
    return this.http.post('api/employees', JSON.stringify(employee), httpOptions)
  }

  deleteEmployee(employeeId: number) {
    return this.http.delete('/api/employees/' + employeeId)
  }

  getEmployee(employeeId: number) {
    return this.http.get('/api/employees/' + employeeId)
  }

  updateEmployee(employee: Employee) {
    return this.http.put('/api/employees/' + employee.id, employee)
  }
}
