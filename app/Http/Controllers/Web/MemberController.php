<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePengurusApplicationRequest;
use App\Services\MemberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function __construct(protected MemberService $memberService) {}

    public function profile()
    {
        $member = Auth::guard('member')->user();
        return view('member.profile', compact('member'));
    }

    public function applyPengurus()
    {
        $member = Auth::guard('member')->user();
        
        if ($member->status_pengurus !== 'tidak_mendaftar') {
            return redirect()->route('member.profile')->with('info', 'Anda sudah atau sedang dalam proses pendaftaran pengurus.');
        }

        return view('member.apply_pengurus', compact('member'));
    }

    public function storePengurusApplication(StorePengurusApplicationRequest $request)
    {
        $member = Auth::guard('member')->user();

        if (!$request->input('is_interested_pengurus')) {
            return redirect()->route('member.profile');
        }

        $data = $request->validated();
        
        // Handle certificate path
        if ($request->hasFile('org_certificate')) {
            $data['org_certificate_path'] = $request->file('org_certificate')->store('certificates', 'public');
        }

        // Update Member data explicitly
        $member->update([
            'is_interested_pengurus' => true,
            'interest_pil'           => $request->boolean('interest_pil', false) ? 1 : 0,
            'interest_keliling'      => $request->boolean('interest_keliling', false) ? 1 : 0,
            'status_pengurus'        => 'pendaftaran_diterima',
            'has_org_experience'     => $data['has_org_experience'],
            'org_name'               => $data['org_name'] ?? null,
            'org_position'           => $data['org_position'] ?? null,
            'org_duration_months'    => $data['org_duration_months'] ?? null,
            'org_description'        => $data['org_description'] ?? null,
            'org_certificate_path'   => $data['org_certificate_path'] ?? $member->org_certificate_path,
        ]);

        return redirect()->route('member.profile')->with('success', 'Permohonan pengurus berhasil dikirim. Menunggu persetujuan admin.');
    }

    public function informations()
    {
        return view('member.informations.index');
    }
}
