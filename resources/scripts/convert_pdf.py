import sys
import os
import shutil
import subprocess
from pathlib import Path


def find_soffice():
    custom = os.environ.get('LIBREOFFICE_PATH', '').strip()
    if custom and os.path.isfile(custom):
        return custom

    if sys.platform == 'win32':
        candidates = [
            r'C:\Program Files\LibreOffice\program\soffice.exe',
            r'C:\Program Files (x86)\LibreOffice\program\soffice.exe',
        ]
        for path in candidates:
            if os.path.isfile(path):
                return path

    for name in ('soffice', 'soffice.exe'):
        found = shutil.which(name)
        if found and _looks_like_libreoffice(found):
            return found

    return None


def _looks_like_libreoffice(path):
    program_dir = os.path.dirname(os.path.abspath(path))
    markers = ('soffice.bin', 'fundamental.ini', 'libuno_sal.dll')
    return any(os.path.isfile(os.path.join(program_dir, marker)) for marker in markers)


def libreoffice_env(program_dir):
    env = os.environ.copy()

    # Strip Python-specific vars so LO's internal Python isn't affected
    for key in list(env.keys()):
        upper = key.upper()
        if upper in ('PYTHONPATH', 'PYTHONHOME', 'PYTHONSTARTUP', 'PYTHONUSERBASE'):
            env.pop(key, None)

    # Remove Laragon's Python from PATH but keep everything else
    path_parts = [
        part for part in env.get('PATH', '').split(os.pathsep)
        if part and 'python' not in part.lower()
    ]
    env['PATH'] = program_dir + os.pathsep + os.pathsep.join(path_parts)

    fundamental = os.path.join(program_dir, 'fundamental.ini')
    if os.path.isfile(fundamental):
        env['URE_BOOTSTRAP'] = f'vnd.sun.star.pathname:{fundamental}'

    return env


def convert(docx_path, pdf_path):
    docx_path = str(Path(docx_path).resolve())
    pdf_path  = str(Path(pdf_path).resolve())

    if not os.path.isfile(docx_path):
        raise FileNotFoundError(f"DOCX file not found: {docx_path}")

    if os.path.getsize(docx_path) == 0:
        raise ValueError(f"DOCX file is empty: {docx_path}")

    final_out_dir = os.path.dirname(pdf_path)
    os.makedirs(final_out_dir, exist_ok=True)

    soffice = find_soffice()
    if not soffice:
        raise EnvironmentError(
            'LibreOffice soffice executable not found. '
            'Install LibreOffice or set LIBREOFFICE_PATH.'
        )

    program_dir = os.path.dirname(os.path.abspath(soffice))
    env         = libreoffice_env(program_dir)

    uid         = os.path.splitext(os.path.basename(docx_path))[0]
    lo_out_dir  = os.path.join(final_out_dir, f'lo_out_{uid}')
    profile_dir = os.path.join(final_out_dir, f'lo_prof_{uid}')
    os.makedirs(lo_out_dir,  exist_ok=True)
    os.makedirs(profile_dir, exist_ok=True)

    user_install = Path(profile_dir).resolve().as_uri()

    command = [
        soffice,
        f'-env:UserInstallation={user_install}',
        '--headless',
        '--nologo',
        '--nofirststartwizard',
        '--convert-to', 'pdf',
        '--outdir', lo_out_dir,
        docx_path,
    ]

    try:
        result = subprocess.run(
            command,
            capture_output=True,
            text=True,
            cwd=program_dir,
            env=env,
        )
    finally:
        shutil.rmtree(profile_dir, ignore_errors=True)

    if result.returncode != 0:
        shutil.rmtree(lo_out_dir, ignore_errors=True)
        raise RuntimeError(
            f"LibreOffice conversion failed (exit {result.returncode}).\n"
            f"soffice: {soffice}\n"
            f"docx: {docx_path}\n"
            f"stdout: {result.stdout.strip()}\n"
            f"stderr: {result.stderr.strip()}"
        )

    generated_pdf = os.path.join(
        lo_out_dir,
        os.path.splitext(os.path.basename(docx_path))[0] + '.pdf',
    )

    if not os.path.isfile(generated_pdf):
        shutil.rmtree(lo_out_dir, ignore_errors=True)
        raise FileNotFoundError(f"Expected output PDF not found: {generated_pdf}")

    shutil.move(generated_pdf, pdf_path)
    shutil.rmtree(lo_out_dir, ignore_errors=True)


if __name__ == '__main__':
    if len(sys.argv) < 3:
        print("Usage: convert_pdf.py <docx_path> <pdf_path>", file=sys.stderr)
        sys.exit(1)

    docx_path = os.path.abspath(sys.argv[1])
    pdf_path  = os.path.abspath(sys.argv[2])

    convert(docx_path, pdf_path)
    print(f"Done: {pdf_path}")