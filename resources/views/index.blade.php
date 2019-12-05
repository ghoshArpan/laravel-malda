@extends('layout.master')
@section('content')

 <?php

  use App\Http\Controllers\ReportController;

  $grievance_report = ReportController::grievance_report();
 
 
 ?>
 <style type="text/css">

 .brik> tbody>tr>td{
    
    padding: 5px;
    font-weight: bold;
    font-size: 18px;

 }

 </style>
     

<div class="row" id="row-content">
    <div class="col-12">                        
        <div class="card">
            <div class="card-body" style="min-height: 400px;">
                <h3 class="card-title">Welcome To Admin Panel</h3>
                <div class="text-center">
                    <span style="font-size: 50px">Malda District Admin Portal</span>
                </div>
                   <table class="brik">
                <tbody>
                    <tr><td width="60%">Total Grievance</td><td width="40%">{{$grievance_report['total_griv']}}</td></tr>
                    <tr><td >Pending Grievance</td><td>{{$grievance_report['pending_griv']}}</td></tr>
                    <tr><td>Total Fowrarded Grievance</td><td>{{$grievance_report['forward_griv']}}</td></tr>
                    <tr><td>Total Resolved Grievance</td><td>{{$grievance_report['resolve_griv']}}</td></tr>
                    <tr><td>Total Closed Grievance</td><td>{{$grievance_report['close_griv']}}</td></tr>

                </tbody>
            </table>
                    
            </div>
            

        </div>
    </div>
</div>
@endsection
@section('script')

@endsection 


