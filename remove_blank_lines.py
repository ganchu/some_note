def remove_blank_lines(file_path):
    with open(file_path, 'r') as file:
        lines = file.readlines()

    # 去除行与行中间的空行
    cleaned_lines = []
    for line in lines:
        if line.strip():  # 判断是否为空行
            cleaned_lines.append(line)

    with open(file_path, 'w') as file:
        file.writelines(cleaned_lines)

    print("已成功去除空行！")

# 有时候复制文字出来后，会在每行中间有个空行，这个可以把空行批量删除
remove_blank_lines('email.txt')
