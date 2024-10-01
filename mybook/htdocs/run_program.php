from flask import Flask, request, render_template, redirect, url_for, send_from_directory, jsonify
import os
import sys  
sys.path.append('/home/ai113/code2')
from method import check


def index():
    return render_template('index.html') 

def upload():
    return render_template('upload.html') 

def uploadVideo():
    if request.method == 'POST':
        if 'video' not in request.files:
            return 'No file part'
        video = request.files['video']
        videoname = video.filename
        video.save(os.path.join('/home/ai113/code2/Flask_website/app/static/dist/video', videoname))
        return redirect(url_for('uploadedVideo',videoname=videoname))

    return "render_template('upload.html')"

def uploadedVideo(videoname):
    return render_template('upload_sussess.html', videoname = videoname) 

def result():
    folder = "/home/ai113/code2/Flask_website/app/static/dist/video_result/result/"
    folder_list = os.listdir(folder)
    image_list = os.listdir(os.path.join(folder, folder_list[0]))
    image_list = ['dist/video_result/result/' + folder_list[0] + '/' + i for i in image_list]
    
    return render_template('result.html', imagelist = image_list)

def run():
    if request.method == 'POST':
        video_path = request.form.get('video_path')
        check(video_path)
        
        filename = (video_path.split('/')[-1]).split('.')[0]
        path = "/home/ai113/code2/Flask_website/app/static/dist/video_result/result"
        path = os.path.join(path, filename)
        zip_path = "/home/ai113/code2/Flask_website/app/static/dist/result_zip"
        zip_path = os.path.join(zip_path, filename)
        zip_command = "zip -r "+ zip_path  +".zip" + " " + path + "/"
        print(zip_command)
        os.system(zip_command)
    return "kkkk"


def chooseVideo():
    folder = "/home/ai113/code2/Flask_website/app/static/dist/video"
    video_list = os.listdir(folder)
    return render_template('choose_video.html', video_list = video_list)



def getFiles():
    try:
        result_path = request.args.get('result_path') 
    
        # 獲取資料夾中的檔案
        files = os.listdir(result_path)
        
        # 過濾出檔案（不是資料夾）
        files = [file for file in files if os.path.isfile(os.path.join(result_path, file))]
        print("hello")
        print(files)
        # 返回 JSON 格式的檔案列表
        return jsonify({'files': files})
    except Exception as e:
        return jsonify({'error': str(e)}), 500

    
