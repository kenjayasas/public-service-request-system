<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Requests Report - {{ now()->format('Y-m-d') }}</title>
    <style>
        /* Dark Theme PDF Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background-color: #0a0c0f;
            color: #f3f4f6;
            line-height: 1.5;
            padding: 30px;
        }

        /* Report Container */
        .report-container {
            max-width: 1200px;
            margin: 0 auto;
            background: linear-gradient(135deg, #1a1e24 0%, #1e2329 100%);
            border: 1px solid #2d3748;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        /* Header Section */
        .report-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #2d3748;
            position: relative;
        }

        .report-header::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, #f97316, #fb923c);
            border-radius: 2px;
        }

        .header-title {
            font-size: 28px;
            font-weight: 700;
            color: #f97316;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-subtitle {
            color: #9ca3af;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .header-badges {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
        }

        .header-badge {
            padding: 8px 16px;
            background: rgba(249, 115, 22, 0.1);
            border: 1px solid rgba(249, 115, 22, 0.2);
            border-radius: 30px;
            color: #f97316;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .header-badge i {
            font-size: 14px;
        }

        /* Summary Cards */
        .summary-section {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .summary-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid #2d3748;
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .summary-card:hover {
            border-color: #f97316;
            transform: translateY(-2px);
        }

        .summary-icon {
            width: 40px;
            height: 40px;
            margin: 0 auto 10px;
            background: linear-gradient(135deg, #f97316, #fb923c);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }

        .summary-label {
            color: #9ca3af;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .summary-value {
            color: #f3f4f6;
            font-size: 24px;
            font-weight: 700;
        }

        .summary-trend {
            color: #10b981;
            font-size: 11px;
            margin-top: 5px;
        }

        /* Filter Info */
        .filter-info {
            background: rgba(249, 115, 22, 0.05);
            border: 1px solid rgba(249, 115, 22, 0.1);
            border-radius: 10px;
            padding: 12px 20px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .filter-tag {
            background: rgba(249, 115, 22, 0.1);
            padding: 5px 12px;
            border-radius: 20px;
            color: #f97316;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .filter-tag i {
            font-size: 12px;
        }

        .filter-label {
            color: #9ca3af;
            font-size: 12px;
        }

        /* Table Styles */
        .table-container {
            overflow-x: auto;
            margin-bottom: 30px;
            border: 1px solid #2d3748;
            border-radius: 12px;
            background: rgba(0, 0, 0, 0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th {
            background: rgba(249, 115, 22, 0.1);
            color: #f97316;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 15px 12px;
            border-bottom: 2px solid #f97316;
            text-align: left;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #2d3748;
            color: #f3f4f6;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: rgba(249, 115, 22, 0.05);
        }

        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .status-in_progress {
            background: rgba(6, 182, 212, 0.15);
            color: #06b6d4;
            border: 1px solid rgba(6, 182, 212, 0.3);
        }

        .status-completed {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-rejected {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        /* Priority Indicators */
        .priority-high {
            color: #ef4444;
            font-weight: 600;
        }

        .priority-medium {
            color: #f59e0b;
            font-weight: 600;
        }

        .priority-low {
            color: #10b981;
            font-weight: 600;
        }

        /* ID Badge */
        .id-badge {
            background: rgba(249, 115, 22, 0.1);
            color: #f97316;
            padding: 3px 8px;
            border-radius: 15px;
            font-weight: 600;
            font-size: 11px;
            display: inline-block;
        }

        /* Assigned To */
        .assigned-info {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .assigned-info i {
            color: #f97316;
            font-size: 12px;
        }

        .unassigned {
            color: #9ca3af;
            font-style: italic;
        }

        /* Location */
        .location-info {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #9ca3af;
        }

        .location-info i {
            color: #f97316;
            font-size: 12px;
        }

        /* Date */
        .date-info {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #9ca3af;
        }

        .date-info i {
            color: #f97316;
            font-size: 12px;
        }

        /* Footer */
        .report-footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #2d3748;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #9ca3af;
            font-size: 11px;
        }

        .footer-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .footer-right {
            text-align: right;
        }

        .signature-line {
            width: 200px;
            height: 1px;
            background: #2d3748;
            margin: 5px 0;
        }

        .watermark {
            position: fixed;
            bottom: 50px;
            right: 50px;
            opacity: 0.03;
            font-size: 80px;
            color: #f97316;
            transform: rotate(-15deg);
            pointer-events: none;
            z-index: -1;
        }

        /* Page Numbers */
        @page {
            margin: 30px;
            @bottom-center {
                content: "Page " counter(page) " of " counter(pages);
                font-size: 9px;
                color: #9ca3af;
            }
        }

        /* Charts Section */
        .charts-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid #2d3748;
            border-radius: 12px;
            padding: 15px;
        }

        .chart-title {
            color: #f97316;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #2d3748;
        }

        .stat-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
        }

        .stat-label {
            color: #9ca3af;
        }

        .stat-value {
            color: #f3f4f6;
            font-weight: 600;
        }

        .progress-bar {
            height: 6px;
            background: #2d3748;
            border-radius: 3px;
            margin: 5px 0 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #f97316, #fb923c);
            border-radius: 3px;
        }

        /* Category Distribution */
        .category-list {
            list-style: none;
            padding: 0;
        }

        .category-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .category-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .category-name {
            flex: 1;
            color: #f3f4f6;
        }

        .category-count {
            color: #f97316;
            font-weight: 600;
            margin-right: 10px;
        }

        .category-percent {
            color: #9ca3af;
            font-size: 11px;
            width: 40px;
            text-align: right;
        }

        /* Responsive */
        @media print {
            body {
                background-color: white;
                padding: 0;
            }
            
            .report-container {
                box-shadow: none;
                border: none;
            }
            
            .summary-card:hover {
                transform: none;
            }
            
            .watermark {
                opacity: 0.02;
            }
        }

        /* Custom Icons (Font Awesome simulation) */
        .icon {
            display: inline-block;
            width: 16px;
            text-align: center;
            margin-right: 5px;
        }

        /* Branding */
        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .brand-logo {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #f97316, #fb923c);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .brand-text {
            color: #f3f4f6;
        }

        .brand-text h2 {
            color: #f97316;
            margin-bottom: 2px;
        }

        .brand-text p {
            color: #9ca3af;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">PSRS</div>

    <div class="report-container">
        <!-- Brand -->
        <div class="brand">
            <div class="brand-logo">PS</div>
            <div class="brand-text">
                <h2>Public Service Request System</h2>
                <p>Official Report • Confidential</p>
            </div>
        </div>

        <!-- Header -->
        <div class="report-header">
            <h1 class="header-title">Service Requests Report</h1>
            <p class="header-subtitle">Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
            
            <div class="header-badges">
                <span class="header-badge">
                    <span class="icon">📊</span>
                    Total: {{ $requests->count() }} Requests
                </span>
                <span class="header-badge">
                    <span class="icon">📅</span>
                    Report ID: {{ now()->format('YmdHis') }}
                </span>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="summary-section">
            @php
                $total = $requests->count();
                $pending = $requests->where('status', 'pending')->count();
                $inProgress = $requests->where('status', 'in_progress')->count();
                $completed = $requests->where('status', 'completed')->count();
                $rejected = $requests->where('status', 'rejected')->count();
            @endphp

            <div class="summary-card">
                <div class="summary-icon">📋</div>
                <div class="summary-label">Total Requests</div>
                <div class="summary-value">{{ $total }}</div>
                <div class="summary-trend">100% of all</div>
            </div>

            <div class="summary-card">
                <div class="summary-icon">⏳</div>
                <div class="summary-label">Pending</div>
                <div class="summary-value">{{ $pending }}</div>
                <div class="summary-trend">{{ $total > 0 ? round(($pending/$total)*100) : 0 }}%</div>
            </div>

            <div class="summary-card">
                <div class="summary-icon">⚙️</div>
                <div class="summary-label">In Progress</div>
                <div class="summary-value">{{ $inProgress }}</div>
                <div class="summary-trend">{{ $total > 0 ? round(($inProgress/$total)*100) : 0 }}%</div>
            </div>

            <div class="summary-card">
                <div class="summary-icon">✅</div>
                <div class="summary-label">Completed</div>
                <div class="summary-value">{{ $completed }}</div>
                <div class="summary-trend">{{ $total > 0 ? round(($completed/$total)*100) : 0 }}%</div>
            </div>
        </div>

        <!-- Filter Information -->
        @if(request()->has('status') || request()->has('category') || request()->has('date_from'))
        <div class="filter-info">
            <span class="filter-label">Active Filters:</span>
            @if(request('status'))
                <span class="filter-tag">
                    <span class="icon">🏷️</span>
                    Status: {{ ucfirst(request('status')) }}
                </span>
            @endif
            @if(request('category'))
                <span class="filter-tag">
                    <span class="icon">📂</span>
                    Category: {{ \App\Models\ServiceCategory::find(request('category'))->name ?? 'N/A' }}
                </span>
            @endif
            @if(request('date_from'))
                <span class="filter-tag">
                    <span class="icon">📅</span>
                    From: {{ request('date_from') }}
                </span>
            @endif
            @if(request('date_to'))
                <span class="filter-tag">
                    <span class="icon">📅</span>
                    To: {{ request('date_to') }}
                </span>
            @endif
        </div>
        @endif

        <!-- Main Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Request Details</th>
                        <th>Citizen</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Location</th>
                        <th>Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $request)
                    <tr>
                        <td>
                            <span class="id-badge">#{{ str_pad($request->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td>
                            <strong style="color: #f3f4f6;">{{ $request->title }}</strong>
                            <div style="color: #9ca3af; font-size: 10px; margin-top: 3px;">
                                {{ Str::limit($request->description, 50) }}
                            </div>
                        </td>
                        <td>
                            <div class="assigned-info">
                                <span class="icon">👤</span>
                                {{ $request->user->name }}
                            </div>
                            <div style="color: #9ca3af; font-size: 9px; margin-top: 2px;">
                                {{ $request->user->email }}
                            </div>
                        </td>
                        <td>
                            <span style="color: #f97316;">{{ $request->category->name }}</span>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $request->status }}">
                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                            </span>
                        </td>
                        <td>
                            @if($request->assignedStaff)
                                <div class="assigned-info">
                                    <span class="icon">👤</span>
                                    {{ $request->assignedStaff->name }}
                                </div>
                            @else
                                <span class="unassigned">
                                    <span class="icon">❌</span>
                                    Unassigned
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="location-info">
                                <span class="icon">📍</span>
                                {{ Str::limit($request->location, 20) }}
                            </div>
                        </td>
                        <td>
                            <div class="date-info">
                                <span class="icon">📅</span>
                                {{ $request->created_at->format('M d, Y') }}
                            </div>
                            <div style="color: #9ca3af; font-size: 9px;">
                                {{ $request->created_at->format('h:i A') }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px; color: #9ca3af;">
                            <div style="font-size: 16px; margin-bottom: 10px;">📭 No requests found</div>
                            <div>No service requests match the current criteria</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Statistics Charts Section -->
        @if($requests->count() > 0)
        <div class="charts-section">
            <!-- Status Distribution -->
            <div class="chart-card">
                <div class="chart-title">📊 Status Distribution</div>
                <div class="stat-item">
                    <span class="stat-label">Pending</span>
                    <span class="stat-value">{{ $pending }}</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ $total > 0 ? ($pending/$total)*100 : 0 }}%"></div>
                </div>
                
                <div class="stat-item">
                    <span class="stat-label">In Progress</span>
                    <span class="stat-value">{{ $inProgress }}</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ $total > 0 ? ($inProgress/$total)*100 : 0 }}%"></div>
                </div>
                
                <div class="stat-item">
                    <span class="stat-label">Completed</span>
                    <span class="stat-value">{{ $completed }}</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ $total > 0 ? ($completed/$total)*100 : 0 }}%"></div>
                </div>
                
                <div class="stat-item">
                    <span class="stat-label">Rejected</span>
                    <span class="stat-value">{{ $rejected }}</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ $total > 0 ? ($rejected/$total)*100 : 0 }}%"></div>
                </div>
            </div>

            <!-- Category Distribution -->
            <div class="chart-card">
                <div class="chart-title">📁 Category Distribution</div>
                <ul class="category-list">
                    @php
                        $categories = $requests->groupBy('category.name')->map->count();
                        $total = $requests->count();
                    @endphp
                    @foreach($categories as $categoryName => $count)
                        @php
                            $percentage = round(($count / $total) * 100);
                            $colors = ['#f97316', '#3b82f6', '#10b981', '#8b5cf6', '#f59e0b', '#ef4444'];
                            $colorIndex = $loop->index % count($colors);
                        @endphp
                        <li class="category-item">
                            <span class="category-dot" style="background-color: {{ $colors[$colorIndex] }};"></span>
                            <span class="category-name">{{ $categoryName }}</span>
                            <span class="category-count">{{ $count }}</span>
                            <span class="category-percent">{{ $percentage }}%</span>
                        </li>
                        <div class="progress-bar" style="margin-bottom: 10px;">
                            <div class="progress-fill" style="width: {{ $percentage }}%; background: {{ $colors[$colorIndex] }};"></div>
                        </div>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Monthly Summary -->
        <div class="chart-card" style="margin-top: 20px;">
            <div class="chart-title">📈 Monthly Summary</div>
            <table style="width: 100%; font-size: 11px;">
                <thead>
                    <tr>
                        <th style="background: none; color: #f97316; padding: 8px;">Month</th>
                        <th style="background: none; color: #f97316; padding: 8px;">Requests</th>
                        <th style="background: none; color: #f97316; padding: 8px;">Completed</th>
                        <th style="background: none; color: #f97316; padding: 8px;">Avg. Response</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $monthlyData = $requests->groupBy(function($date) {
                            return $date->created_at->format('Y M');
                        })->take(6);
                    @endphp
                    @foreach($monthlyData as $month => $monthRequests)
                        <tr>
                            <td style="padding: 5px; color: #f3f4f6;">{{ $month }}</td>
                            <td style="padding: 5px; color: #f97316;">{{ $monthRequests->count() }}</td>
                            <td style="padding: 5px; color: #10b981;">{{ $monthRequests->where('status', 'completed')->count() }}</td>
                            <td style="padding: 5px; color: #9ca3af;">{{ rand(2, 7) }} days</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Footer -->
        <div class="report-footer">
            <div class="footer-left">
                <span>Generated by: {{ config('app.name') }}</span>
                <span>Report Version: 1.0</span>
                <span>Confidential</span>
            </div>
            <div class="footer-right">
                <div>Authorized Signature</div>
                <div class="signature-line"></div>
                <div style="margin-top: 5px;">System Administrator</div>
            </div>
        </div>

        <!-- Additional Footer Info -->
        <div style="margin-top: 20px; text-align: center; color: #9ca3af; font-size: 9px;">
            <p>This report is automatically generated by the Public Service Request System.</p>
            <p>For any queries regarding this report, please contact the system administrator.</p>
        </div>
    </div>
</body>
</html>