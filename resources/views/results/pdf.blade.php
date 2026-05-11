<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>SciEval — Quiz Result Report</title>
<style>
    body { font-family: 'DejaVu Sans', sans-serif; color: #1a1a2e; margin: 0; padding: 20px; }
    .header { background: linear-gradient(135deg, #0f0c29, #302b63); color: white; padding: 30px; border-radius: 12px; margin-bottom: 20px; }
    .logo { font-size: 24px; font-weight: bold; margin-bottom: 5px; }
    .subtitle { font-size: 12px; opacity: 0.7; }
    .score-box { background: #f0f9ff; border: 2px solid #0ea5e9; border-radius: 12px; padding: 20px; text-align: center; margin-bottom: 20px; }
    .score-big { font-size: 60px; font-weight: bold; color: #0ea5e9; }
    .badge { display: inline-block; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: bold; }
    .badge-pass { background: #dcfce7; color: #16a34a; }
    .badge-fail { background: #fee2e2; color: #dc2626; }
    .section { margin-bottom: 20px; }
    .section-title { font-size: 14px; font-weight: bold; color: #0f172a; border-bottom: 2px solid #e2e8f0; padding-bottom: 8px; margin-bottom: 12px; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #f8fafc; padding: 8px 12px; text-align: left; font-size: 11px; color: #64748b; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
    td { padding: 8px 12px; font-size: 12px; border-bottom: 1px solid #f1f5f9; }
    .correct { color: #16a34a; font-weight: bold; }
    .wrong { color: #dc2626; }
    .progress-row { margin-bottom: 8px; }
    .progress-label { display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 3px; }
    .progress-bar { background: #e2e8f0; border-radius: 4px; height: 8px; }
    .progress-fill { background: #0ea5e9; border-radius: 4px; height: 8px; }
    .footer { text-align: center; font-size: 10px; color: #94a3b8; margin-top: 20px; padding-top: 10px; border-top: 1px solid #e2e8f0; }
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .info-item { background: #f8fafc; border-radius: 8px; padding: 10px; }
    .info-label { font-size: 10px; color: #64748b; text-transform: uppercase; margin-bottom: 3px; }
    .info-value { font-size: 14px; font-weight: bold; color: #0f172a; }
</style>
</head>
<body>

<div class="header">
    <div class="logo">🔬 SciEval</div>
    <div class="subtitle">Scientific Temper & Literacy Evaluation Platform</div>
    <div style="margin-top: 10px; font-size: 18px; font-weight: bold;">Quiz Result Report</div>
    <div style="font-size: 12px; opacity: 0.7; margin-top: 3px;">Generated: {{ now()->format('d M Y, H:i') }}</div>
</div>

<div class="section">
    <div class="section-title">Student Information</div>
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Student Name</div>
            <div class="info-value">{{ $attempt->user->name }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Email</div>
            <div class="info-value">{{ $attempt->user->email }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Quiz</div>
            <div class="info-value">{{ $attempt->quiz->title }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Category</div>
            <div class="info-value">{{ $attempt->quiz->category->name }}</div>
        </div>
    </div>
</div>

@if($result)
<div class="score-box">
    <div class="score-big">{{ $result->score_percentage }}%</div>
    <div style="margin: 8px 0;">
        <span class="badge {{ $result->passed ? 'badge-pass' : 'badge-fail' }}">
            {{ $result->passed ? '✓ PASSED' : '✗ FAILED' }}
        </span>
    </div>
    <div style="font-size: 13px; color: #64748b;">
        {{ $result->score }} / {{ $result->total_questions }} correct · Literacy Level: {{ $result->literacy_level }}
    </div>
</div>

<div class="section">
    <div class="section-title">Scientific Temper Analysis</div>
    @foreach([
        ['Logical Thinking', $result->logical_thinking_score],
        ['Evidence Reasoning', $result->evidence_reasoning_score],
        ['Myth vs Fact', $result->myth_vs_fact_score],
        ['Problem Solving', $result->problem_solving_score],
    ] as $skill)
    <div class="progress-row">
        <div class="progress-label"><span>{{ $skill[0] }}</span><span>{{ $skill[1] }}%</span></div>
        <div class="progress-bar"><div class="progress-fill" style="width: {{ $skill[1] }}%"></div></div>
    </div>
    @endforeach
</div>

<div class="section">
    <div class="section-title">Feedback</div>
    <p style="font-size: 12px; color: #374151; line-height: 1.6;">{{ $result->feedback }}</p>
</div>
@endif

<div class="section">
    <div class="section-title">Answer Sheet</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Question</th>
                <th>Your Answer</th>
                <th>Result</th>
            </tr>
        </thead>
        <tbody>
            @foreach($answers as $i => $answer)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td style="font-size: 11px;">{{ Str::limit($answer->question->question_text, 60) }}</td>
                <td style="font-size: 11px;">{{ $answer->selectedOption ? Str::limit($answer->selectedOption->option_text, 40) : 'Not answered' }}</td>
                <td class="{{ $answer->is_correct ? 'correct' : 'wrong' }}">{{ $answer->is_correct ? '✓' : '✗' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="footer">
    This report was generated by SciEval — Scientific Temper & Literacy Evaluation Platform<br>
    Report ID: {{ $attempt->id }} | Date: {{ now()->format('d M Y') }}
</div>

</body>
</html>
