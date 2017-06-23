using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Data.Entity;
using System.Drawing;
using System.Linq;
using System.Web;

namespace Medical_teleconference.Models
{
    public class Comment
    {
        [Key]
        [DatabaseGeneratedAttribute(DatabaseGeneratedOption.Identity)]
        public int CommentId { get; set; }
        [Required]
        public int RoomId { get; set; }
        [Required]
        public int UserId { get; set; }
        [Required]
        [StringLength(300, ErrorMessage = "Maximal length of the message is 300 characters!")]
        public string Message { get; set; }
        [Required]
        [DataType(DataType.Date)]
        public DateTime Date { get; set; }

        /*public Comment()
        {
            this.Date = DateTime.Now;
        }*/
    }
}