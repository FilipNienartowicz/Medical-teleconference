﻿using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Data.Entity;
using System.Drawing;
using System.Linq;
using System.Web;

namespace Medical_teleconference.Models
{
    [Table("Comments")]
    public class Comment : DbContext
    {
        [Key]
        [DatabaseGeneratedAttribute(DatabaseGeneratedOption.Identity)]
        public int CommentId { get; set; }
        public int RoomId { get; set; }
        public int UserId { get; set; }
        [StringLength(300, ErrorMessage = "Maximal length of the message is 300 characters!")]
        public string Message { get; set; }
        public DateTime Date { get; set; }
    }
}