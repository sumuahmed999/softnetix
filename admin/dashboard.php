<?php
/**
 * Admin Dashboard - Main Page
 * SOFTNETIX
 */

session_start();

// Check if logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

define('SECURE_ACCESS', true);
require_once '../api/config.php';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = Database::getInstance()->getConnection();
    
    if (isset($_POST['mark_read'])) {
        $id = (int)$_POST['id'];
        $stmt = $db->prepare("UPDATE contact_submissions SET status = 'read', read_at = NOW() WHERE id = ?");
        $stmt->execute([$id]);
    } elseif (isset($_POST['mark_replied'])) {
        $id = (int)$_POST['id'];
        $stmt = $db->prepare("UPDATE contact_submissions SET status = 'replied' WHERE id = ?");
        $stmt->execute([$id]);
    } elseif (isset($_POST['archive'])) {
        $id = (int)$_POST['id'];
        $stmt = $db->prepare("UPDATE contact_submissions SET status = 'archived' WHERE id = ?");
        $stmt->execute([$id]);
    } elseif (isset($_POST['delete'])) {
        $id = (int)$_POST['id'];
        $stmt = $db->prepare("DELETE FROM contact_submissions WHERE id = ?");
        $stmt->execute([$id]);
    }
    
    header('Location: dashboard.php');
    exit;
}

// Get filter
$filter = $_GET['filter'] ?? 'all';
$search = $_GET['search'] ?? '';

// Build query
$db = Database::getInstance()->getConnection();
$sql = "SELECT * FROM contact_submissions WHERE 1=1";
$params = [];

if ($filter !== 'all') {
    $sql .= " AND status = ?";
    $params[] = $filter;
}

if (!empty($search)) {
    $sql .= " AND (name LIKE ? OR email LIKE ? OR message LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

$sql .= " ORDER BY submitted_at DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$submissions = $stmt->fetchAll();

// Get statistics
$stats = [
    'total' => $db->query("SELECT COUNT(*) FROM contact_submissions")->fetchColumn(),
    'new' => $db->query("SELECT COUNT(*) FROM contact_submissions WHERE status = 'new'")->fetchColumn(),
    'read' => $db->query("SELECT COUNT(*) FROM contact_submissions WHERE status = 'read'")->fetchColumn(),
    'replied' => $db->query("SELECT COUNT(*) FROM contact_submissions WHERE status = 'replied'")->fetchColumn(),
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SOFTNETIX Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f8fafc;
            color: #1e293b;
        }
        
        .header {
            background: linear-gradient(135deg, #4FD1C5 0%, #63B3ED 100%);
            color: white;
            padding: 1.25rem 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .logo-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }
        
        .logo-icon svg {
            width: 24px;
            height: 24px;
        }
        
        .header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.025em;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50px;
            backdrop-filter: blur(10px);
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .user-avatar svg {
            width: 18px;
            height: 18px;
            color: #4FD1C5;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s;
        }
        
        .logout-btn svg {
            width: 16px;
            height: 16px;
        }
        
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-1px);
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.75rem;
            border-radius: 16px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--card-color), var(--card-color-light));
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .stat-card-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--card-bg);
        }
        
        .stat-icon svg {
            width: 24px;
            height: 24px;
            color: var(--card-color);
        }
        
        .stat-card h3 {
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }
        
        .stat-card .number {
            font-size: 2.25rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1;
        }
        
        .stat-card.total {
            --card-color: #4FD1C5;
            --card-color-light: #81E6D9;
            --card-bg: #E6FFFA;
        }
        
        .stat-card.new {
            --card-color: #3B82F6;
            --card-color-light: #60A5FA;
            --card-bg: #EFF6FF;
        }
        
        .stat-card.read {
            --card-color: #F59E0B;
            --card-color-light: #FBBF24;
            --card-bg: #FEF3C7;
        }
        
        .stat-card.replied {
            --card-color: #10B981;
            --card-color-light: #34D399;
            --card-bg: #D1FAE5;
        }
        
        .filters {
            background: white;
            padding: 1.5rem;
            border-radius: 16px;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .filter-btn {
            padding: 0.625rem 1.25rem;
            border: 2px solid #e2e8f0;
            background: white;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            color: #475569;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .filter-btn svg {
            width: 16px;
            height: 16px;
        }
        
        .filter-btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }
        
        .filter-btn.active {
            background: linear-gradient(135deg, #4FD1C5, #63B3ED);
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 6px -1px rgba(79, 209, 197, 0.3);
        }
        
        .search-box {
            flex: 1;
            min-width: 280px;
            position: relative;
        }
        
        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: #94a3b8;
            pointer-events: none;
        }
        
        .search-box input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.875rem;
            transition: all 0.2s;
            background: #f8fafc;
        }
        
        .search-box input:focus {
            outline: none;
            border-color: #4FD1C5;
            background: white;
            box-shadow: 0 0 0 3px rgba(79, 209, 197, 0.1);
        }
        
        .submissions {
            background: white;
            border-radius: 16px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
        
        .submission-item {
            padding: 1.75rem;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s;
        }
        
        .submission-item:hover {
            background: #f8fafc;
        }
        
        .submission-item:last-child {
            border-bottom: none;
        }
        
        .submission-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
            gap: 1rem;
        }
        
        .submission-info h3 {
            color: #0f172a;
            font-size: 1.125rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .submission-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            color: #64748b;
            font-size: 0.875rem;
        }
        
        .meta-item {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }
        
        .meta-item svg {
            width: 16px;
            height: 16px;
            color: #94a3b8;
        }
        
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            white-space: nowrap;
        }
        
        .status-badge svg {
            width: 14px;
            height: 14px;
        }
        
        .status-new {
            background: #EFF6FF;
            color: #3B82F6;
            border: 1px solid #BFDBFE;
        }
        
        .status-read {
            background: #FEF3C7;
            color: #F59E0B;
            border: 1px solid #FDE68A;
        }
        
        .status-replied {
            background: #D1FAE5;
            color: #10B981;
            border: 1px solid #A7F3D0;
        }
        
        .status-archived {
            background: #F1F5F9;
            color: #64748B;
            border: 1px solid #E2E8F0;
        }
        
        .submission-message {
            color: #475569;
            line-height: 1.7;
            margin-bottom: 1.25rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 10px;
            border-left: 3px solid #4FD1C5;
        }
        
        .submission-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        
        .action-btn {
            padding: 0.625rem 1.125rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .action-btn svg {
            width: 16px;
            height: 16px;
        }
        
        .btn-read {
            background: #FEF3C7;
            color: #D97706;
            border: 1px solid #FDE68A;
        }
        
        .btn-read:hover {
            background: #FDE68A;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.2);
        }
        
        .btn-replied {
            background: #D1FAE5;
            color: #059669;
            border: 1px solid #A7F3D0;
        }
        
        .btn-replied:hover {
            background: #A7F3D0;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);
        }
        
        .btn-archive {
            background: #F1F5F9;
            color: #475569;
            border: 1px solid #E2E8F0;
        }
        
        .btn-archive:hover {
            background: #E2E8F0;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(100, 116, 139, 0.2);
        }
        
        .btn-delete {
            background: #FEE2E2;
            color: #DC2626;
            border: 1px solid #FECACA;
        }
        
        .btn-delete:hover {
            background: #FECACA;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(220, 38, 38, 0.2);
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #94a3b8;
        }
        
        .empty-state-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: #f1f5f9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .empty-state-icon svg {
            width: 40px;
            height: 40px;
            color: #cbd5e1;
        }
        
        .empty-state h3 {
            font-size: 1.25rem;
            color: #475569;
            margin-bottom: 0.5rem;
        }
        
        .empty-state p {
            font-size: 0.875rem;
        }
        
        @media (max-width: 768px) {
            .header {
                padding: 1rem 1.5rem;
            }
            
            .header-left {
                flex-direction: column;
                align-items: start;
                gap: 0.5rem;
            }
            
            .header h1 {
                font-size: 1.25rem;
            }
            
            .header-right {
                flex-direction: column;
                gap: 0.75rem;
                width: 100%;
            }
            
            .user-info {
                width: 100%;
                justify-content: center;
            }
            
            .logout-btn {
                width: 100%;
                justify-content: center;
            }
            
            .stats {
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
                gap: 1rem;
            }
            
            .stat-card {
                padding: 1.25rem;
            }
            
            .stat-card .number {
                font-size: 1.875rem;
            }
            
            .filters {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filter-btn {
                width: 100%;
                justify-content: center;
            }
            
            .submission-header {
                flex-direction: column;
                gap: 0.75rem;
            }
            
            .submission-actions {
                flex-direction: column;
            }
            
            .action-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <div class="logo-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <h1>SOFTNETIX Admin</h1>
        </div>
        <div class="header-right">
            <div class="user-info">
                <div class="user-avatar">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <span class="user-name"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
            </div>
            <a href="logout.php" class="logout-btn">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Logout
            </a>
        </div>
    </div>
    
    <div class="container">
        <!-- Statistics -->
        <div class="stats">
            <div class="stat-card total">
                <div class="stat-card-header">
                    <div>
                        <h3>Total Submissions</h3>
                        <div class="number"><?php echo $stats['total']; ?></div>
                    </div>
                    <div class="stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="stat-card new">
                <div class="stat-card-header">
                    <div>
                        <h3>New Messages</h3>
                        <div class="number"><?php echo $stats['new']; ?></div>
                    </div>
                    <div class="stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="stat-card read">
                <div class="stat-card-header">
                    <div>
                        <h3>Read</h3>
                        <div class="number"><?php echo $stats['read']; ?></div>
                    </div>
                    <div class="stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="stat-card replied">
                <div class="stat-card-header">
                    <div>
                        <h3>Replied</h3>
                        <div class="number"><?php echo $stats['replied']; ?></div>
                    </div>
                    <div class="stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="filters">
            <a href="?filter=all" class="filter-btn <?php echo $filter === 'all' ? 'active' : ''; ?>">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                </svg>
                All
            </a>
            <a href="?filter=new" class="filter-btn <?php echo $filter === 'new' ? 'active' : ''; ?>">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                </svg>
                New
            </a>
            <a href="?filter=read" class="filter-btn <?php echo $filter === 'read' ? 'active' : ''; ?>">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Read
            </a>
            <a href="?filter=replied" class="filter-btn <?php echo $filter === 'replied' ? 'active' : ''; ?>">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                </svg>
                Replied
            </a>
            <a href="?filter=archived" class="filter-btn <?php echo $filter === 'archived' ? 'active' : ''; ?>">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
                Archived
            </a>
            
            <form method="GET" class="search-box">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="hidden" name="filter" value="<?php echo htmlspecialchars($filter); ?>">
                <input type="text" name="search" placeholder="Search by name, email, or message..." value="<?php echo htmlspecialchars($search); ?>">
            </form>
        </div>
        
        <!-- Submissions -->
        <div class="submissions">
            <?php if (empty($submissions)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                    <h3>No submissions found</h3>
                    <p>There are no contact form submissions matching your criteria.</p>
                </div>
            <?php else: ?>
                <?php foreach ($submissions as $submission): ?>
                    <div class="submission-item">
                        <div class="submission-header">
                            <div class="submission-info">
                                <h3><?php echo htmlspecialchars($submission['name']); ?></h3>
                                <div class="submission-meta">
                                    <span class="meta-item">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <?php echo htmlspecialchars($submission['email']); ?>
                                    </span>
                                    <?php if ($submission['phone']): ?>
                                        <span class="meta-item">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            <?php echo htmlspecialchars($submission['phone']); ?>
                                        </span>
                                    <?php endif; ?>
                                    <span class="meta-item">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <?php echo date('M d, Y H:i', strtotime($submission['submitted_at'])); ?>
                                    </span>
                                </div>
                            </div>
                            <span class="status-badge status-<?php echo $submission['status']; ?>">
                                <?php if ($submission['status'] === 'new'): ?>
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                    </svg>
                                <?php elseif ($submission['status'] === 'read'): ?>
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                <?php elseif ($submission['status'] === 'replied'): ?>
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                <?php else: ?>
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                    </svg>
                                <?php endif; ?>
                                <?php echo $submission['status']; ?>
                            </span>
                        </div>
                        
                        <div class="submission-message">
                            <?php echo nl2br(htmlspecialchars($submission['message'])); ?>
                        </div>
                        
                        <div class="submission-actions">
                            <?php if ($submission['status'] === 'new'): ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $submission['id']; ?>">
                                    <button type="submit" name="mark_read" class="action-btn btn-read">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Mark as Read
                                    </button>
                                </form>
                            <?php endif; ?>
                            
                            <?php if ($submission['status'] !== 'replied'): ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $submission['id']; ?>">
                                    <button type="submit" name="mark_replied" class="action-btn btn-replied">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Mark as Replied
                                    </button>
                                </form>
                            <?php endif; ?>
                            
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="id" value="<?php echo $submission['id']; ?>">
                                <button type="submit" name="archive" class="action-btn btn-archive">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                    </svg>
                                    Archive
                                </button>
                            </form>
                            
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this submission?');">
                                <input type="hidden" name="id" value="<?php echo $submission['id']; ?>">
                                <button type="submit" name="delete" class="action-btn btn-delete">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
